<?php

namespace App\Models\Base;

use App\Library\QueryBuilder\Filters\AllowedFilter;
use App\Library\QueryBuilder\Filters\Custom\FilterLike;
use App\Library\QueryBuilder\Filters\Custom\FilterMulti;
use App\Models\Base\IrModel;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IrModelFeature extends BaseModel
{

    public $timestamps = false;

    private static  $modelClassToTableMap = [];
    private static  $modelClassToOptionMap = [];
    protected $guarded = ['id', 'created_at', 'updated_at'];
    /**
     * Protected constructor to make sure this is called from either
     * DynamicModelLayerFeature::table or internal static methods of Model. Otherwise, we cannot
     * track the class name related to the table
     */
    protected function __construct(array $attributes = [], ?string $table = null, IrModel $layer = null)
    {
        if (isset($table)) {
            // use table passed from DynamicModelLayerFeature::table
            $this->setTable($table);
        } elseif (isset(self::$modelClassToTableMap[\get_class($this)])) {
            // restore used table from map while internally creating new instances
            $this->setTable(self::$modelClassToTableMap[\get_class($this)]);
        } else {
            throw new \LogicException('Call DynamicModelLayerFeature::table to get a new instance and be able use any static model method.');
        }
        if (isset($layer)) {
            $this->setLayer($layer);
        } else if (isset(self::$modelClassToOptionMap[\get_class($this)])) {
            $this->setOption(self::$modelClassToOptionMap[\get_class($this)]);
        }
        parent::__construct($attributes);
    }
    public static function feature($feature, IrModel $layer)
    {
        return new class($feature, $layer->table_name, $layer) extends IrModelFeature
        {
        };
    }
    public function setLayer(IrModel $layer)
    {
        if (empty($layer)) {
            return;
        }
        $with = [];
        $fillable = [];
        $search = [];
        $filter = [];
        $sort = [];
        $include = [];
        if ($layer->relationLoaded('fields')) {
            $fields = $layer['fields'];
            $fillable = [];
            $with = [];
            foreach ($fields as $column) {
                if (!in_array($column['code'], $this->guarded))
                    $fillable[] = $column['code'];
                if ($column['search_by_fulltext_search'] === '1-Always Searchable')
                    $search[] = $column['code'];
                if (!empty($column['relation'])) {
                    $relation_field = $column['relation_field'] ?? 'id:name';
                    $splits = explode(":", $relation_field);
                    $field_id = $splits[0] ?? 'id';
                    $field_value = $splits[1] ?? 'name';
                    $field_relation_id = $splits[2] ?? null;
                    $filter[] = AllowedFilter::custom($field_relation_id ?? $column['code'], new FilterMulti);
                } else if ($column['type'] === 'string') {
                    $filter[] = AllowedFilter::custom($column['code'], new FilterLike);
                } else {
                    $filter[] = $column['code'];
                }
                $sort[] = $column['code'];
            }
        }
        $this->setOption([
            'fillable' => $fillable,
            'with' => $with ?? [],
            'search' => $search ?? [],
            'filter' => $filter ?? [],
            'sort' => $sort ?? [],
            'include' => $include ?? [],
        ]);
    }
    public function setOption($options)
    {
        self::$modelClassToOptionMap[\get_class($this)] = $options;
        $this->fillable = $options['fillable'] ?? [];
        $this->with = $options['with'] ?? [];
        self::$INCLUDE = $options['include'] ?? [];
        self::$SORT = $options['sort'] ?? [];
        self::$SEARCH = $options['search'] ?? [];
        self::$FILTER =
            $options['filter'] ?? [];
    }

    public function getCauserDisplay()
    {
        return $this[$this->fieldLog];
    }
    public static function table(string $table, IrModel $layer = null): self
    {
        return new class([], $table, $layer) extends IrModelFeature
        {
        };
    }

    public function setTable($table): self
    {
        self::$modelClassToTableMap[\get_class($this)] = $table;

        return parent::setTable($table);
    }
}
