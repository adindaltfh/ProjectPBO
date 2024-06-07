<?php
namespace App\Model;

use PDO;
use App\Model\Task;
use App\Model\Theme;
use App\Model\Model;

class Planner extends Model
{
    public int $id_planner;
    public Theme $theme;
    public Task $task;
    public string $to_do;
    public bool $status;

    public function getAll()
    {
        $sql = "SELECT `id_planner`,
                        `theme`.`color`,
                        `theme`.`texture`,
                        `theme`.`background`,
                        `task`.`title`,
                        `task`.`description`,
                        `task`.`category`,
                        `task`.`due_date`,
                        `task`.`reminder_at`,
                        `planner`.`to_do`,
                        `planner`.`status`
                FROM `planner`
                LEFT JOIN `theme`
                    ON `theme`.`id_theme` = `planner`.`id_theme`
                LEFT JOIN `task`
                    ON `task`.`id_task` = `planner`.`id_task`";

        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            $planner = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $planner = null;
        }
        return $planner;
    }

    public function detail($id_planner)
    {
        $stmt = $this->db->prepare("SELECT * FROM planner WHERE id_planner=$id_planner");
        if ($stmt->execute()) {
            $planner = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id_planner = $planner['id_planner'];


            $this->to_do = $planner['to_do'];
            $this->status = $planner['status'];
        } else {
            $planner = null;
        }
    }

    public function save(): bool
    {
        $stmt = $this->db->prepare("INSERT INTO planner (id_planner, id_theme, id_task, to_do, status) VALUES (:id_planner, :id_theme, :id_task, :to_do, :status)");
        $this->generateId();
        $stmt->bindParam(':id_planner', $this->id_planner);
        $stmt->bindParam(':id_theme', $this->theme->id_theme); 
        $stmt->bindParam(':id_task', $this->task->id_task); 
        $stmt->bindParam(':to_do', $this->to_do);
        $stmt->bindParam(':status', $this->status);
        return $stmt->execute();
    }    

    public function generateId()
    {
        $sql = 'SELECT MAX(id_planner) AS id_planner FROM planner';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id_planner = $data['id_planner'] + 1;
    }

    public function getTasks(): array
    {
        $tasks = [];
    
        $sql = "SELECT task.* FROM task
                JOIN planner ON task.id_task = planner.id_task
                WHERE planner.id_planner = :id_planner";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_planner', $this->id_planner);
    
        if ($stmt->execute()) {
            while ($taskData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $task = new Task();
                $task->id_task = $taskData['id_task'];
                $task->title = $taskData['title'];
                $task->description = $taskData['description'];
                $task->category = $taskData['category'];
                $task->due_date = $taskData['due_date'];
                $task->reminder_at = $taskData['reminder_at'];
                $task->repeat_on = $taskData['repeat_on'];
    
                $tasks[] = $task;
            }
        }
    
        return $tasks;
    }       

    public function markAsComplete(): void
    {
        $this->status = true;
    }

    public function removeTask(Task $task): bool
    {
        $sql = "DELETE task FROM task
                JOIN planner ON task.id_task = planner.id_task
                WHERE task.id_task = :id_task AND planner.id_planner = :id_planner";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_task', $task->id_task);
        $stmt->bindParam(':id_planner', $this->id_planner);
    
        return $stmt->execute();
    }     
}