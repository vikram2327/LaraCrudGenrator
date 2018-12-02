<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CrudGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'command:name';

    protected $signature = 'crud:generator
    {name : Class (singular) for example User}';
    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';

    protected $description = 'Create CRUD operations';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //

        $name = $this->argument('name');

        $this->controller($name);
        $this->model($name);
        $this->request($name);

        File::append(base_path('routes/api.php'), 'Route::resource(\'' . str_plural(strtolower($name)) . "', '{$name}\{$name}Controller');");

    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }

    protected function model($name)
    {
        $modelTemplate = str_replace(
            ['{{modelName}}'],
            [$name],
        $this->getStub('Model')
        );

          if(!file_exists($path = app_path('/Model')))
            mkdir($path, 0777, true);
        file_put_contents(app_path("/Model/{$name}.php"), $modelTemplate);
    }

    protected function controller($name)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}'
            ],
            [
                $name,
                strtolower(str_plural($name)),
                strtolower($name)
            ],
            $this->getStub('Controller')
        );
         mkdir(app_path("/Http/Controllers/{$name}"), 0777, true);
        file_put_contents(app_path("/Http/Controllers/{$name}/{$name}Controller.php"), $controllerTemplate);
    }
    protected function request($name)
    {
        $requestTemplate = str_replace(
            ['{{modelName}}'],
            [$name],
            $this->getStub('Request')
        );

        if(!file_exists($path = app_path('/Http/Requests')))
            mkdir($path, 0777, true);
         mkdir(app_path("/Http/Requests/{$name}"), 0777, true);
        file_put_contents(app_path("/Http/Requests/{$name}/{$name}Request.php"), $requestTemplate);
    }


}
    