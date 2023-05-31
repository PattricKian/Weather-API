<?php



require_once "MyPDO.php";

class Location
{
    /* @var MyPDO */
    protected $db;

    protected int $id;
    protected String $location;
    protected String $country;
    protected String $capital;
    protected float $latitude;
    protected float $longitude;
    protected string $local_time;

    /**
     * @return string
     */
    public function getLocalTime(): string
    {
        return $this->local_time;
    }

    /**
     * @param string $local_time
     */
    public function setLocalTime(string $local_time): void
    {
        $this->local_time = $local_time;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return String
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param String $location
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    /**
     * @return String
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param String $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return String
     */
    public function getCapital(): string
    {
        return $this->capital;
    }

    /**
     * @param String $capital
     */
    public function setCapital(string $capital): void
    {
        $this->capital = $capital;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function __construct()
    {
        $this->db = MyPDO::instance();
    }

    public function save()
    {

        $latlng = 'POINT('.$this->latitude." ".$this->longitude.')';
        $sql = 'INSERT INTO locations (location, country, capital, latlng, local_time) 
                VALUES (:location, :country, :capital, ST_GeomFromText(:latlng), :localtime)';
        $this->db->run($sql, [":location" => $this->location, ":country" => $this->country, ":capital" => $this->capital ?? null, ":latlng" => $latlng, ":localtime" => $this->local_time]);
        $this->id = $this->db->lastInsertId();
    }

    public static function findById($id)
    {
        $location = MyPDO::instance()->run("SELECT location, country, capital, local_time FROM locations WHERE id = ?", [$id])->fetch();
        $pos = MyPDO::instance()->run("SELECT ST_X(`latlng`) as Xpos, ST_Y(`latlng`) as Ypos FROM locations WHERE id = ?", [$id])->fetch();
        $location = json_decode(json_encode($location), TRUE);
        $pos = json_decode(json_encode($pos), TRUE);
        $location['lat'] = $pos['Xpos'];
        $location['lng'] = $pos['Ypos'];
        return $location;
    }

    public static function findAll()
    {
        $locations = MyPDO::instance()->run("SELECT id, location, country, capital, local_time FROM locations")->fetchAll();
        for ($i = 0; $i < count($locations); $i++) {
            $locations[$i] = json_decode(json_encode($locations[$i]), TRUE);
            $pos = MyPDO::instance()->run("SELECT ST_X(`latlng`) as Xpos, ST_Y(`latlng`) as Ypos FROM locations WHERE id = ?", [$locations[$i]['id']])->fetch();
            $pos = json_decode(json_encode($pos), TRUE);
            $locations[$i]['lat'] = $pos['Xpos'];
            $locations[$i]['lng'] = $pos['Ypos'];
        }
        return $locations;
    }

    public function toArray()
    {
        return [
                "location"=>$this->location,
                "country" => $this->country,
                "capital_city" => $this->capital,
                "latitude" => $this->latitude,
                "longitude" => $this->longitude,
                ];
    }
}

?>


