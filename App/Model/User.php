<?php
namespace App\Model;

use PDO;
use App\Model\Planner;
use App\Model\Model;

class User extends Model
{
    public int $id_user;
    public Planner $planner;
    public string $photo;
    public string $name;
    public string $email;
    private string $password;
    public int $complete_task;
    public int $pending_task;

    public function getAll()
    {
        $sql = "SELECT 
                    u.id_user,
                    p.status,
                    u.photo,
                    u.name,
                    u.email,
                    u.password,
                    u.complete_task,
                    u.pending_task,
                    w.opacity,
                    w.size,
                    w.time_scope,
                    t.title AS task_title,
                    t.due_date AS task_due_date,
                    t.reminder_at AS task_reminder_at,
                    th.color,
                    th.texture,
                    th.background
                FROM user u
                LEFT JOIN planner p ON u.id_planner = p.id_planner
                LEFT JOIN widget w ON w.id_widget = w.id_widget
                LEFT JOIN task t ON p.id_task = t.id_task
                LEFT JOIN theme th ON p.id_theme = th.id_theme";
    
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $users = null;
        }
        return $users;
    }        


    public function detail($id_user)
    {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE id_user=$id_user");
        if ($stmt->execute()) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id_user = $user['id_user'];

            // Set the associated planner
            $this->planner = new Planner();
            $this->planner->id_planner = $user['id_planner'];

            $this->photo = $user['photo'];
            $this->name = $user['name'];
            $this->email = $user['email'];
            $this->password = $user['password'];
            $this->complete_task = $user['complete_task'];
            $this->pending_task = $user['pending_task'];
        } else {
            $user = null;
        }
    }

    public function editPhoto(): bool
    {
        $stmt = $this->db->prepare("UPDATE user SET photo = :photo WHERE id_user = :id_user");
        $stmt->bindParam(':photo', $this->photo);
        $stmt->bindParam(':id_user', $this->id_user);
        return $stmt->execute();
    } 

    public function setPhoto($photo): void
    {
        $this->photo = $photo;
    }

    public function save(): bool
{
    $existingUser = $this->findById('user', 'id_user', $this->id_user);

    if ($existingUser) {
        $stmt = $this->db->prepare("UPDATE user SET id_planner = :id_planner, photo = :photo, name = :name, email = :email, password = :password, complete_task = :complete_task, pending_task = :pending_task WHERE id_user = :id_user");

        $stmt->bindParam(':id_user', $this->id_user);
        $stmt->bindParam(':id_planner', $this->planner->id_planner);
        $stmt->bindParam(':photo', $this->photo);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':complete_task', $this->complete_task);
        $stmt->bindParam(':pending_task', $this->pending_task);
    } else {
        $stmt = $this->db->prepare("INSERT INTO user (id_user, id_planner, photo, name, email, password, complete_task, pending_task) VALUES (:id_user, :id_planner, :photo, :name, :email, :password, :complete_task, :pending_task)");

        $stmt->bindParam(':id_user', $this->id_user);
        $stmt->bindParam(':id_planner', $this->planner->id_planner);
        $stmt->bindParam(':photo', $this->photo);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':complete_task', $this->complete_task);
        $stmt->bindParam(':pending_task', $this->pending_task);
    }

    return $stmt->execute();
}   
    

    public function generateId()
    {
        $sql = 'SELECT MAX(id_user) AS id_user FROM user';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id_user = $data['id_user'] + 1;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }   

    public function hitungCompleteTask(): void
    {
        $this->complete_task = count(array_filter($this->planner->getTasks(), function ($task) {
            return $task->status === true;
        }));
    }
    
    public function hitungPendingTask(): void
    {
        $this->pending_task = count(array_filter($this->planner->getTasks(), function ($task) {
            return $task->status === false;
        }));
    }    
}
