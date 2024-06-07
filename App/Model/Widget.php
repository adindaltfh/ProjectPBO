<?php
namespace App\Model;

use PDO;
use App\Model\Model;
use App\Model\Planner;

class Widget extends Model
{
    public int $id_widget;
    public Planner $planner;
    public int $opacity;
    public string $size;
    public string $time_scope;
    public string $category_filter;

    public function getAll()
    {
        $sql = "SELECT `id_widget`,
                        `opacity`,
                        `size`,
                        `time_scope`,
                        `category_filter`,
                        `planner`.`to_do`,
                        `planner`.`status`
                FROM `widget`
                LEFT JOIN `planner`
                    ON `planner`.`id_planner` = `widget`.`id_planner`";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            $widget = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $widget = null;
        }
        return $widget;
    }

    public function detail($id_widget)
    {
        $stmt = $this->db->prepare("SELECT * FROM widget WHERE id_widget=$id_widget");
        if ($stmt->execute()) {
            $widget = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id_widget = $widget['id_widget'];
            $this->opacity = $widget['opacity'];
            $this->size = $widget['size'];
            $this->time_scope = $widget['time_scope'];
            $this->category_filter = $widget['category_filter'];
            
            // Set the associated planner
            $this->planner = new planner();
            $this->planner->id_planner = $widget['id_planner'];
            $this->planner->to_do = $widget['to_do'];
            $this->planner->status = $widget['status'];
        } else {
            $widget = null;
        }
    }

    public function save(): bool
    {
        $stmt = $this->db->prepare("INSERT INTO widget (id_widget, opacity, size, time_scope, category_filter, id_planner) VALUES (:id_widget, :opacity, :size, :time_scope, :category_filter, :id_planner)");
        $this->generateId();
        $stmt->bindParam(':id_widget', $this->id_widget);
        $stmt->bindParam(':opacity', $this->opacity);
        $stmt->bindParam(':size', $this->size);
        $stmt->bindParam(':time_scope', $this->time_scope);
        $stmt->bindParam(':category_filter', $this->category_filter);
        $stmt->bindParam(':id_planner', $this->planner->id_planner);
        return $stmt->execute();
    }

    public function generateId()
    {
        $sql = 'SELECT MAX(id_widget) AS id_widget FROM widget';
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id_widget = $data['id_widget'] + 1;
        } else {
            return 0;
        }
    }
    
    public function applyWidget ($newOpacity, $newSize, $time, $filter): void
    {
        $this->opacity = $newOpacity;
        $this->size = $newSize;
        $this->time_scope = $time;
        $this->category_filter = $filter;
    }

    public function selectByUser()
    {
        return "Widget Opacity    : {$this->opacity} \nWidget Size       : {$this->size} \nWidget Time Scope : {$this->time_scope}\n";
    }

}
