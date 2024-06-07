<?php
namespace App\Model;

use PDO;
use App\Model\Model;
use DateTime;

class Task extends Model
{
    public int $id_task;
    public string $title;
    public string $description;
    public string $category;
    public string $due_date;
    public string $reminder_at;
    public string $repeat_on;

    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM task");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function detail($id_task)
    {
        $stmt = $this->db->prepare("SELECT * FROM task WHERE id_task=:id_task");
        $stmt->bindParam(':id_task', $id_task);
        $stmt->execute();
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($task) {
            $this->id_task = $task['id_task'];
            $this->title = $task['title'];
            $this->description = $task['description'];
            $this->category = $task['category'];
            $this->due_date = $task['due_date'];
            $this->reminder_at = $task['reminder_at'];
            $this->repeat_on = $task['repeat_on'];
        }
    }

    public function save(): bool
    {
        $stmt = $this->db->prepare("INSERT INTO task (id_task, title, description, category, due_date, reminder_at, repeat_on) 
        VALUES (:id_task, :title, :description, :category, :due_date, :reminder_at, :repeat_on)");
        $stmt->bindParam(':id_task', $this->id_task);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':due_date', $this->due_date);
        $stmt->bindParam(':reminder_at', $this->reminder_at);
        $stmt->bindParam(':repeat_on', $this->repeat_on);
        return $stmt->execute();
    }

    public function generateId()
    {
        $sql = 'SELECT MAX(id_task) AS id_task FROM task';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data['id_task'] + 1;
    }

    public function idTask($new_id)
    {
        $this->id_task = $new_id;
    }

    public function addTask($newTitle, $setCategory, $setDue, $setReminder)
    {
        $this->title = $newTitle;
        $this->category = $setCategory;

        if ($setDue instanceof DateTime) {
            $this->due_date = $setDue->format('Y-m-d');
        } else {
            $this->due_date = (new DateTime($setDue))->format('Y-m-d');
        }
    
        $this->reminder_at = $setReminder;
    }
    

    public function addDescription($newDescription)
    {
        $this->description = $newDescription;
    }

    public function addRepeat($setRepeat)
    {
        $this->repeat_on = $setRepeat;
    }

    public function newTask() {
        echo "Task Title        : {$this->title} \nTask Description  : {$this->description} \nTask Category     : {$this->category}\nTask Due Date     : {$this->due_date}\nTask Reminder At  : {$this->reminder_at}\nTask Repeat On    : {$this->repeat_on}\n";
    }
}
