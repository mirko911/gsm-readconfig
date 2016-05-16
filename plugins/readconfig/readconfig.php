<?php
namespace GSM\Plugins\Readconfig;

use GSM\Daemon\Core\Utils;

/**
 * Reloads the server config every x mins
 *
 * @author mirko911
 * @since 11.06.14
 */
class Readconfig extends Utils {

    private $job_id;

    public function initPlugin() {
        parent::initPlugin();

        $this->config->setDefault("readconfig", "enabled", true);
        $this->config->setDefault("readconfig", "time", 60);
    }

    public function enable() {
        parent::enable();
        $this->job_id = $this->jobs->addSingleJob($this->config->get("readconfig", "time"), array($this, "configAutoLoad"));
    }

    public function disable() {
        parent::disable();
        $this->jobs->deleteJob($this->job_id);
    }

    /**
     * Reloads the server config every x mins
     *
     * @return boolean if success or fail ;-)
     */
    function configAutoLoad() {
        $this->mod->readConfig();
        $this->job_id = $this->jobs->addSingleJob($this->config->get("readconfig", "time"), array($this, "configAutoLoad"));
        return true;
    }
}
