<?php
class Song {

    private $con;
    private $id;
    private $database_data;
    private $title;
    private $artistId;
    private $albumId;
    private $genre;
    private $duration;
    private $path;

    public function __construct($con, $id) {
        $this->con = $con;
        $this->id = $id;
        $query = mysqli_query($this->con, "SELECT * FROM  songs WHERE id='$this->id'");
        $this->database_data = mysqli_fetch_array($query);
        $this->title = $this->database_data['title'];
        $this->artistId = $this->database_data['artist'];
        $this->albumId = $this->database_data['album'];
        $this->genre = $this->database_data['genre'];
        $this->duration = $this->database_data['duration'];
        $this->path = $this->database_data['path'];
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getArtist() {
        return new Artist($this->con, $this->artistId);
    }

    public function getAlbum() {
        return new Album($this->con, $this->albumId);
    }

    public function getGenre() {
        return new Album($this->con, $this->genre);
    }

    public function getDuration() {
        return $this->duration;
    }

    public function getPath() {
        return $this->path;
    }

    public function getDatabaseData() {
        return $this->database_data;
    }


}
