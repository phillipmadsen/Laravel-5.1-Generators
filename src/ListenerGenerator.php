<?php namespace SKAgarwal\Generators;

use Illuminate\Support\Facades\Artisan;
use SKAgarwal\Generators\Traits\ListenerGeneratableTrait;

class ListenerGenerator extends Generator
{
    use ListenerGeneratableTrait {
        config as listenerConfig;
    }

    /**
     * Name of the Listener
     *
     * @var String
     */
    protected $name;

    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * Generate the listener.
     *
     * @param       $name
     * @param array $options
     */
    public function generate($name, $options = [])
    {
        $this->name = ucfirst($name);
        $this->config($options['model']);

        $this->setArguments($options);
        Artisan::call('make:listener', $this->arguments);
    }


    /**
     * Check if listener should be queued.
     *
     * @param $queued
     *
     * @return string
     */
    private function isQueued($queued)
    {
        return $queued ? '--queued' : '';
    }

    /**
     * @param array $arguments
     */
    public function setArguments($options)
    {
        $queued = $this->isQueued($options['queued']);

        $this->arguments = [
            'name'     => "$this->listenerNamespace\\{$this->name}",
            '--queued' => $queued,
        ];

        $this->addEventIfPresent($options['event']);
    }

    /**
     * Set the --event argument if present.
     *
     * @param $event
     */
    private function addEventIfPresent($event)
    {
        if ($event) {
            $event = ucfirst($event);
            $this->arguments['--event'] = $this->eventNamespace . "\\{$event}";
        }
    }

    /**
     * Configure Listerer Generator
     *
     * @param $model
     */
    protected function config($model)
    {
        parent::config($model);
        $this->listenerConfig($this->model, $this->namespace);
    }
}
