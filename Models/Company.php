<?php namespace Foostart\Branch\Models;

use Foostart\Category\Library\Models\FooModel;
use Illuminate\Database\Eloquent\Model;
use Foostart\Comment\Models\Comment;

class Branch extends FooModel {

    /**
     * @table categories
     * @param array $attributes
     */
    public $user = NULL;
    public function __construct(array $attributes = array()) {
        //set configurations
        $this->setConfigs();

        parent::__construct($attributes);

    }

    public function setConfigs() {

        //table name
        $this->table = 'branch';

        //list of field in table
        $this->fillable = [
            'branch_name',
            'branch_slug',
            'category_id',
            'slideshow_id',
            'user_id',
            'user_full_name',
            'user_email',
            'branch_overview',
            'branch_description',
            'branch_image',
            'branch_files',
            'branch_status',
        ];

        //list of fields for inserting
        $this->fields = [
            'branch_name' => [
                'name' => 'branch_name',
                'type' => 'Text',
            ],
            'branch_slug' => [
                'name' => 'branch_slug',
                'type' => 'Text',
            ],
            'category_id' => [
                'name' => 'category_id',
                'type' => 'Int',
            ],
            'slideshow_id' => [
                'name' => 'slideshow_id',
                'type' => 'Int',
            ],
            'user_id' => [
                'name' => 'user_id',
                'type' => 'Int',
            ],
            'user_full_name' => [
                'name' => 'user_full_name',
                'type' => 'Text',
            ],
            'user_email' => [
                'name' => 'email',
                'type' => 'Text',
            ],
            'branch_overview' => [
                'name' => 'branch_overview',
                'type' => 'Text',
            ],
            'branch_description' => [
                'name' => 'branch_description',
                'type' => 'Text',
            ],
            'branch_image' => [
                'name' => 'branch_image',
                'type' => 'Text',
            ],
            'branch_files' => [
                'name' => 'files',
                'type' => 'Json',
            ],
            'branch_status' => [
                'name' => 'status',
                'type' => 'Int',
            ],
        ];

        //check valid fields for inserting
        $this->valid_insert_fields = [
            'branch_name',
            'branch_slug',
            'user_id',
            'category_id',
            'slideshow_id',
            'user_full_name',
            'updated_at',
            'branch_overview',
            'branch_description',
            'branch_image',
            'branch_files',
            'branch_status',
        ];

        //check valid fields for ordering
        $this->valid_ordering_fields = [
            'branch_name',
            'updated_at',
            $this->field_status,
        ];
        //check valid fields for filter
        $this->valid_filter_fields = [
            'keyword',
            'status',
            'category',
            '_id',
            'limit',
            'branch_id!',
            'category_id',
            'user_id',
        ];

        //primary key
        $this->primaryKey = 'branch_id';

        //the number of items on page
        $this->perPage = 10;

        //item status
        $this->field_status = 'branch_status';

    }

    /**
     * Gest list of items
     * @param type $params
     * @return object list of categories
     */
    public function selectItems($params = array()) {

        //join to another tables
        $elo = $this->joinTable();

        //search filters
        $elo = $this->searchFilters($params, $elo);

        //select fields
        $elo = $this->createSelect($elo);

        //order filters
        $elo = $this->orderingFilters($params, $elo);

        //paginate items
        if ($this->is_pagination) {
            $items = $this->paginateItems($params, $elo);
        } else {
            $items = $elo->get();
        }

        return $items;
    }

    /**
     * Get a branch by {id}
     * @param ARRAY $params list of parameters
     * @return OBJECT branch
     */
    public function selectItem($params = array(), $key = NULL) {


        if (empty($key)) {
            $key = $this->primaryKey;
        }
       //join to another tables
        $elo = $this->joinTable();

        //search filters
        $elo = $this->searchFilters($params, $elo, FALSE);

        //select fields
        $elo = $this->createSelect($elo);

        //id
        $elo = $elo->where($this->primaryKey, $params['id']);

        //first item
        $item = $elo->first();

        return $item;
    }


    public function getComments($branch_id) {

        // Get branch
        $params = array(
            'id' => $branch_id,
        );
        $branch = $this->selectItem($params);

        // Get comment by context
        $params = array(
            'context_name' => 'branch',
            'context_id' => $branch_id,
            'by_status' => true,
        );
        $obj_comment = new Comment();
        $obj_comment->user = $this->user;
        $comments = $obj_comment->selectItems($params);

        $users_comments = $obj_comment->mapCommentArray($comments);
        $branch->cache_comments = json_encode($users_comments);
        $branch->cache_time = time();
        $branch->save();

        return $users_comments;
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @return ELOQUENT OBJECT
     */
    protected function joinTable(array $params = []){
        return $this;
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @return ELOQUENT OBJECT
     */
    protected function searchFilters(array $params = [], $elo, $by_status = TRUE){

        //filter
        if ($this->isValidFilters($params) && (!empty($params)))
        {
            foreach($params as $column => $value)
            {
                if($this->isValidValue($value))
                {
                    switch($column)
                    {
                        case 'category_id':
                            if (!empty($value)) {
                                $elo = $elo->where($this->table . '.category_id', '=', $value);
                            }
                            break;
                        case 'category':
                            if (!empty($value)) {
                                $elo = $elo->where($this->table . '.category_id', '=', $value);
                            }
                            break;
                        case 'user_id':
                            if (!empty($value)) {
                                $elo = $elo->where($this->table . '.user_id', '=', $value);
                            }
                            break;
                        case 'limit':
                            if (!empty($value)) {
                                $this->perPage = $value;
                                $elo = $elo->limit($value);
                            }
                            break;
                        case '_id':
                            if (!empty($value)) {
                                $elo = $elo->where($this->table . '.branch_id', '!=', $value);
                            }
                            break;
                        case 'status':
                            if (!empty($value)) {
                                $elo = $elo->where($this->table . '.'.$this->field_status, '=', $value);
                            }
                            break;
                        case 'keyword':
                            if (!empty($value)) {
                                $elo = $elo->where(function($elo) use ($value) {
                                    $elo->where($this->table . '.branch_name', 'LIKE', "%{$value}%")
                                    ->orWhere($this->table . '.branch_description','LIKE', "%{$value}%")
                                    ->orWhere($this->table . '.branch_overview','LIKE', "%{$value}%");
                                });
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
        } elseif ($by_status) {

            $elo = $elo->where($this->table . '.'.$this->field_status, '=', $this->status['publish']);

        }

        return $elo;
    }

    /**
     * Select list of columns in table
     * @param ELOQUENT OBJECT
     * @return ELOQUENT OBJECT
     */
    public function createSelect($elo) {

        $elo = $elo->select($this->table . '.*',
                            $this->table . '.branch_id as id'
                );

        return $elo;
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @return ELOQUENT OBJECT
     */
    public function paginateItems(array $params = [], $elo) {
        $items = $elo->paginate($this->perPage);

        return $items;
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @param INT $id is primary key
     * @return type
     */
    public function updateItem($params = [], $id = NULL) {

        if (empty($id)) {
            $id = $params['id'];
        }
        $field_status = $this->field_status;

        //get branch item by conditions
        $_params = [
            'id' => $id,
        ];
        $branch = $this->selectItem($_params);

        if (!empty($branch)) {
            $dataFields = $this->getDataFields($params, $this->fields);

            foreach ($dataFields as $key => $value) {
                $branch->$key = $value;
            }

            $branch->save();

            return $branch;
        } else {
            return NULL;
        }
    }


    /**
     *
     * @param ARRAY $params list of parameters
     * @return OBJECT branch
     */
    public function insertItem($params = []) {

        $dataFields = $this->getDataFields($params, $this->fields);

        $dataFields[$this->field_status] = $this->status['publish'];


        $item = self::create($dataFields);

        $key = $this->primaryKey;
        $item->id = $item->$key;

        return $item;
    }


    /**
     *
     * @param ARRAY $input list of parameters
     * @return boolean TRUE incase delete successfully otherwise return FALSE
     */
    public function deleteItem($input = [], $delete_type) {

        $item = $this->find($input['id']);

        if ($item) {
            switch ($delete_type) {
                case 'delete-trash':
                    return $item->fdelete($item);
                    break;
                case 'delete-forever':
                    return $item->delete();
                    break;
            }

        }

        return FALSE;
    }

    public function getCoursesByCategoriesRoot($categories) {

        $this->is_pagination = false;

        if (!empty($categories)) {

            //get courses of category root
            $_params = [
                'limit' => 9,
                'category' => $categories->category_id,
                'is_pagination' => false
            ];
            $categories->courses = $this->selectItems($_params);

            //get courses of category childs
            foreach ($categories->childs as $key => $category) {
                $ids = [$category->category_id => 1];
                if (!empty($category->category_id_child_str)) {
                    $ids += (array)json_decode($category->category_id_child_str);;
                }
                $ids = array_keys($ids);

                //error
                $_temp = $categories->childs[$key];
                $_temp->courses = $this->getCouresByCategoryIds($ids);
            }


        }
        return $categories;
    }

    public function getCouresByCategoryIds($ids) {
        $courses = self::whereIn('category_id', $ids)
                    ->paginate($this->perPage);
        return $courses;
    }


    public function getItemsByCategories($categories) {

        $items = [];
        $ids = [];

        foreach ($categories as $category ) {
            $ids += [$category->category_id => 1];

            if (!empty($category->category_id_child_str)) {
                $ids += (array) json_decode($category->category_id_child_str);
            }
        }

        //Get list of items by ids
        $items = $this->getCouresByCategoryIds(array_keys($ids));

        return $items;
    }

    }