<?php
namespace App\Model;

use PDO;
use App\Model\Model;

class Theme extends Model
{
    public int $id_theme;
    public string $color;
    public string $texture;
    public string $background;

    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM theme");
        if ($stmt->execute()) {
            $themes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $themes = null;
        }
        return $themes;
    }

    public function detail($id_theme)
    {
        $stmt = $this->db->prepare("SELECT * FROM theme WHERE id_theme=$id_theme");
        if ($stmt->execute()) {
            $theme = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id_theme = $theme['id_theme'];
            $this->color = $theme['color'];
            $this->texture = $theme['texture'];
            $this->background = $theme['background'];
        } else {
            $theme = null;
        }
    }

    public function save(): bool
    {
        $stmt = $this->db->prepare("INSERT INTO theme (id_theme, color, texture, background) VALUES (:id_theme, :color, :texture, :background)");
        $this->generateId();
        $stmt->bindParam(':id_theme', $this->id_theme);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':texture', $this->texture);
        $stmt->bindParam(':background', $this->background);
        return $stmt->execute();
    }

    public function generateId()
    {
        $sql = 'SELECT MAX(id_theme) AS id_theme FROM theme';
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id_theme = $data['id_theme'] + 1;
        } else {
            return 0;
        }
    }

    function applyTheme ($newColor, $newTexture, $newBackground) : void {
        $this->color = $newColor;
        $this->texture = $newTexture;
        $this->background = $newBackground;
    }
    
    public function selectByUser() {
        return "Theme Color       : {$this->color} \nTheme Texture     : {$this->texture} \nTheme Background     : {$this->background}";
    }
}