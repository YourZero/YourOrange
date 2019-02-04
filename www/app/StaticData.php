<?php
namespace YourOrange;

class StaticData
{
    /**
     * @var string
     */
    protected $version;
    /**
     * @var string
     */
    protected $dir = "/tmp";
    /**
     * @var string
     */
    protected $fullFilename;
    /**
     * @var string
     */
    protected $fullDirName;
    /**
     * @var string
     */
    protected $dirName;
    /**
     * @var string
     */
    protected $filename;

    /**
     * StaticData constructor.
     * @param $version
     */
    public function __construct($version)
    {
        $this->version = $version;
        $this->dirName = $this->getDirName();
        $this->filename = $this->getFilename();
        $this->fullFilename = "{$this->dir}/{$this->filename}";
        $this->fullDirName = "{$this->dir}/{$this->dirName}";
        $this->createDir();
        $this->getDataFile();
    }

    /**
     * Updated Database with loaded version
     */
    public function updateChampions()
    {
        $data = $this->getChampions();
        foreach($data->data as $data) {
            $champion = Champion::load($data->id);
            $champion->fill($data);
            $champion->save();
        }
    }
    /**
     * Updated Database with loaded version
     */
    public function updateItems()
    {
        $data = $this->getItems();
        foreach($data->data as $key => $data) {
            $champion = Item::load($key);
            $champion->fill(array_merge((array)$data, ['id' => $key]));
            $champion->save();
        }
    }

    /**
     * Updated Database with loaded version
     */
    public function updateIt()
    {
        $data = $this->getItems();
        foreach($data->data as $key => $data) {
            $champion = Item::load($key);
            $champion->fill(array_merge((array)$data, ['id' => $key]));
            $champion->save();
        }
    }

    /**
     * @return string
     */
    protected function getDirName()
    {
        return "dragontail-{$this->version}";
    }

    /**
     * @return string
     */
    protected function getFilename()
    {
        return "{$this->getDirName()}.tgz";
    }

    /**
     * Downloads and extracts version file if not present
     */
    protected function getDataFile()
    {
        if(!file_exists($this->fullFilename)) {
            $url = "https://ddragon.leagueoflegends.com/cdn/{$this->getFilename()}";
            exec("wget -O {$this->fullFilename} {$url}");
            exec("tar xvzf {$this->fullFilename} -C {$this->fullDirName}");
        }
    }

    //Todo: People might want other languages, have some sort of way to select language options.

    /**
     * @return object
     */
    protected function getChampions()
    {
        return json_decode(file_get_contents("{$this->fullDirName}/{$this->version}/data/en_GB/champion.json"));
    }

    /**
     * @return object
     */
    protected function getItems()
    {
        return json_decode(file_get_contents("{$this->fullDirName}/{$this->version}/data/en_GB/item.json"));
    }

    /**
     * Creates temp dir if doesn't exist
     */
    protected function createDir()
    {
        if(!is_dir($this->fullDirName)) {
            mkdir($this->fullDirName);
        }
    }
}
