<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Models\ProductAttributeValue;

class ProductRepository extends Repository
{
    /**
     * AttributeRepository object
     *
     * @var \Webkul\Attribute\Repositories\AttributeRepository
     */
    protected $attributeRepository;

    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Illuminate\Container\Container  $app
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        App $app
    )
    {
        $this->attributeRepository = $attributeRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return 'Webkul\Product\Contracts\Product';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Product\Contracts\Product
     */
    public function create(array $data)
    {
        Event::dispatch('catalog.product.create.before');

        $typeInstance = app(config('product_types.' . $data['type'] . '.class'));

        $product = $typeInstance->create($data);

        Event::dispatch('catalog.product.create.after', $product);

        return $product;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attribute = "id")
    {
        Event::dispatch('catalog.product.update.before', $id);

        $product = $this->find($id);

        $product = $product->getTypeInstance()->update($data, $id, $attribute);

        if (isset($data['channels'])) {
            $product['channels'] = $data['channels'];
        }

        Event::dispatch('catalog.product.update.after', $product);

        return $product;
    }

    /**
     * @param  int  $id
     * @return void
     */
    public function delete($id)
    {
        Event::dispatch('catalog.product.delete.before', $id);

        parent::delete($id);

        Event::dispatch('catalog.product.delete.after', $id);
    }

    /**
     * @param  int  $categoryId
     * @return \Illuminate\Support\Collection
     */
    public function getAll($categoryId = null)
    {
        $params = request()->input();

        $results = app(ProductFlatRepository::class)->scopeQuery(function($query) use($params, $categoryId) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            $qb = $query->distinct()
                        ->addSelect('product_flat.*')
                        ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                        ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
                        ->where('product_flat.channel', $channel)
                        ->where('product_flat.locale', $locale)
                        ->whereNotNull('product_flat.url_key');

            if ($categoryId) {
                $qb->where('product_categories.category_id', $categoryId);
            }

            if (is_null(request()->input('status'))) {
                $qb->where('product_flat.status', 1);
            }

            if (is_null(request()->input('visible_individually'))) {
                $qb->where('product_flat.visible_individually', 1);
            }

            $queryBuilder = $qb->leftJoin('product_flat as flat_variants', function($qb) use($channel, $locale) {
                $qb->on('product_flat.id', '=', 'flat_variants.parent_id')
                    ->where('flat_variants.channel', $channel)
                    ->where('flat_variants.locale', $locale);
            });

            if (isset($params['search']))
                $qb->where('product_flat.name', 'like', '%' . urldecode($params['search']) . '%');

            if (isset($params['sort'])) {
                $attribute = $this->attributeRepository->findOneByField('code', $params['sort']);

                if ($attribute) {
                    if ($params['sort'] == 'price') {
                        if ($attribute->code == 'price') {
                            $qb->orderBy('min_price', $params['order']);
                        } else {
                            $qb->orderBy($attribute->code, $params['order']);
                        }
                    } else {
                        $qb->orderBy($params['sort'] == 'created_at' ? 'product_flat.created_at' : $attribute->code, $params['order']);
                    }
                }
            }

            $qb = $qb->leftJoin('products as variants', 'products.id', '=', 'variants.parent_id');

            $qb = $qb->where(function($query1) use($qb) {
                $aliases = [
                    'products' => 'filter_',
                    'variants' => 'variant_filter_',
                ];

                foreach($aliases as $table => $alias) {
                    $query1 = $query1->orWhere(function($query2) use ($qb, $table, $alias) {

                        foreach ($this->attributeRepository->getProductDefaultAttributes(array_keys(request()->input())) as $code => $attribute) {
                            $aliasTemp = $alias . $attribute->code;

                            $qb = $qb->leftJoin('product_attribute_values as ' . $aliasTemp, $table . '.id', '=', $aliasTemp . '.product_id');

                            $column = ProductAttributeValue::$attributeTypeFields[$attribute->type];

                            $temp = explode(',', request()->get($attribute->code));

                            if ($attribute->type != 'price') {
                                $query2 = $query2->where($aliasTemp . '.attribute_id', $attribute->id);

                                $query2 = $query2->where(function($query3) use($aliasTemp, $column, $temp) {
                                    foreach($temp as $code => $filterValue) {
                                        if (! is_numeric($filterValue)) {
                                            continue;
                                        }

                                        $columns = $aliasTemp . '.' . $column;
                                        $query3 = $query3->orwhereRaw("find_in_set($filterValue, $columns)");
                                    }
                                });
                            } else {
                                $query2->where('product_flat.min_price', '>=', core()->convertToBasePrice(current($temp)))
                                        ->where('product_flat.min_price', '<=', core()->convertToBasePrice(end($temp)));
                            }
                        }
                    });
                }
            });

            return $qb->groupBy('product_flat.id');
        })->paginate(isset($params['limit']) ? $params['limit'] : 9);

        return $results;
    }

    /**
     * Retrive product from slug
     *
     * @param  string  $slug
     * @param  string  $columns
     * @return \Webkul\Product\Contracts\Product
     */
    public function findBySlugOrFail($slug, $columns = null)
    {
        $product = app(ProductFlatRepository::class)->findOneWhere([
            'url_key' => $slug,
            'locale'  => app()->getLocale(),
            'channel' => core()->getCurrentChannelCode(),
        ]);

        if (! $product) {
            throw (new ModelNotFoundException)->setModel(
                get_class($this->model), $slug
            );
        }

        return $product;
    }

    public function random($limit = 5)
    {
        return app(ProductFlatRepository::class)
        ->where("locale",app()->getLocale())
        ->where("channel",core()->getCurrentChannelCode())
        ->whereNotNull("url_key")
        ->whereNotNull("sku")
        ->where('product_flat.status', 1)
        ->inRandomOrder()->limit($limit)->get()->map(function ($item, $key) {
            return $item->product()->first();
        });
    }

    /**
     * Retrieve product from slug without throwing an exception (might return null)
     *
     * @param  string  $slug
     * @return \Webkul\Product\Contracts\ProductFlat
     */
    public function findBySlug($slug)
    {
        return app(ProductFlatRepository::class)->findOneWhere([
            'url_key' => $slug,
            'locale'  => app()->getLocale(),
            'channel' => core()->getCurrentChannelCode(),
        ]);
    }

    /**
     * Returns newly added product
     *
     * @return \Illuminate\Support\Collection
     */
    public function getNewProducts()
    {
        $results = app(ProductFlatRepository::class)->scopeQuery(function($query) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            return $query->distinct()
                            ->addSelect('product_flat.*')
                            ->where('product_flat.status', 1)
                            ->where('product_flat.visible_individually', 1)
                            ->where('product_flat.new', 1)
                            ->where('product_flat.channel', $channel)
                            ->where('product_flat.locale', $locale)
                            ->orderBy('product_id', 'desc');
        })->paginate(4);

        return $results;
    }

    /**
     * Returns featured product
     *
     * @return \Illuminate\Support\Collection
     */
    public function getFeaturedProducts()
    {
        $results = app(ProductFlatRepository::class)->scopeQuery(function($query) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            return $query->distinct()
                            ->addSelect('product_flat.*')
                            ->where('product_flat.status', 1)
                            ->where('product_flat.visible_individually', 1)
                            ->where('product_flat.featured', 1)
                            ->where('product_flat.channel', $channel)
                            ->where('product_flat.locale', $locale)
                            ->orderBy('product_id', 'desc');
        })->paginate(10);

        return $results;
    }

    /**
     * Search Product by Attribute
     *
     * @param  string  $term
     * @return \Illuminate\Support\Collection
     */
    public function searchProductByAttribute($term)
    {
        $results = app(ProductFlatRepository::class)->scopeQuery(function($query) use($term) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            return $query->distinct()
                            ->addSelect('product_flat.*')
                            ->where('product_flat.status', 1)
                            ->where('product_flat.visible_individually', 1)
                            ->where('product_flat.channel', $channel)
                            ->where('product_flat.locale', $locale)
                            ->whereNotNull('product_flat.url_key')
                            ->where(function($sub_query) use ($term) {  
                                $sub_query->where('product_flat.name', 'like', '%' . urldecode($term) . '%')
                                          ->orWhere('product_flat.short_description', 'like', '%' . urldecode($term) . '%');
                                })
                            ->orderBy('product_id', 'desc');
        })->paginate(16);

        return $results;
    }

    /**
     * Returns product's super attribute with options
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return \Illuminate\Support\Collection
     */
    public function getSuperAttributes($product)
    {
        $superAttrbutes = [];

        foreach ($product->super_attributes as $key => $attribute) {
            $superAttrbutes[$key] = $attribute->toArray();

            foreach ($attribute->options as $option) {
                $superAttrbutes[$key]['options'][] = [
                    'id'           => $option->id,
                    'admin_name'   => $option->admin_name,
                    'sort_order'   => $option->sort_order,
                    'swatch_value' => $option->swatch_value,
                ];
            }
        }

        return $superAttrbutes;
    }

    /**
     * Search simple products for grouped product association
     *
     * @param  string  $term
     * @return \Illuminate\Support\Collection
     */
    public function searchSimpleProducts($term)
    {
        return app(ProductFlatRepository::class)->scopeQuery(function($query) use($term) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            return $query->distinct()
                         ->addSelect('product_flat.*')
                         ->addSelect('product_flat.product_id as id')
                         ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                         ->where('products.type', 'simple')
                         ->where('product_flat.channel', $channel)
                         ->where('product_flat.locale', $locale)
                         ->where('product_flat.name', 'like', '%' . urldecode($term) . '%')
                         ->orderBy('product_id', 'desc');
        })->get();
    }
}