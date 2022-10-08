<?php namespace Foostart\Branch\Validators;

use Foostart\Category\Library\Validators\FooValidator;
use Event;
use \LaravelAcl\Library\Validators\AbstractValidator;
use Foostart\Branch\Models\Branch;

use Illuminate\Support\MessageBag as MessageBag;

class BranchValidator extends FooValidator
{

    protected $obj_branch;

    public function __construct()
    {
        // add rules
        self::$rules = [
            'branch_name' => ["required"],
            'branch_overview' => ["required"],
            'branch_description' => ["required"],
        ];

        // set configs
        self::$configs = $this->loadConfigs();

        // model
        $this->obj_branch = new Branch();

        // language
        $this->lang_front = 'branch-front';
        $this->lang_admin = 'branch-admin';

        // event listening
        Event::listen('validating', function($input)
        {
            self::$messages = [
                'branch_name.required'          => trans($this->lang_admin.'.errors.required', ['attribute' => trans($this->lang_admin.'.fields.name')]),
                'branch_overview.required'      => trans($this->lang_admin.'.errors.required', ['attribute' => trans($this->lang_admin.'.fields.overview')]),
                'branch_description.required'   => trans($this->lang_admin.'.errors.required', ['attribute' => trans($this->lang_admin.'.fields.description')]),
            ];
        });


    }

    /**
     *
     * @param ARRAY $input is form data
     * @return type
     */
    public function validate($input) {

        $flag = parent::validate($input);
        $this->errors = $this->errors ? $this->errors : new MessageBag();

        //Check length
        $_ln = self::$configs['length'];

        $params = [
            'name' => [
                'key' => 'branch_name',
                'label' => trans($this->lang_admin.'.fields.name'),
                'min' => $_ln['branch_name']['min'],
                'max' => $_ln['branch_name']['max'],
            ],
            'overview' => [
                'key' => 'branch_overview',
                'label' => trans($this->lang_admin.'.fields.overview'),
                'min' => $_ln['branch_overview']['min'],
                'max' => $_ln['branch_overview']['max'],
            ],
            'description' => [
                'key' => 'branch_description',
                'label' => trans($this->lang_admin.'.fields.description'),
                'min' => $_ln['branch_description']['min'],
                'max' => $_ln['branch_description']['max'],
            ],
        ];

        $flag = $this->isValidLength($input['branch_name'], $params['name']) ? $flag : FALSE;
        $flag = $this->isValidLength($input['branch_overview'], $params['overview']) ? $flag : FALSE;
        $flag = $this->isValidLength($input['branch_description'], $params['description']) ? $flag : FALSE;

        return $flag;
    }


    /**
     * Load configuration
     * @return ARRAY $configs list of configurations
     */
    public function loadConfigs(){

        $configs = config('package-branch');
        return $configs;
    }

}