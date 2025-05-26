<?php
    
    require_once(__DIR__ . '/../config/connection.php');
        
    class UsersModel extends Connection{
        private $table;

        function __construct()
        {
            parent::__construct();
            $this->table = 'users';
        }

        
        function getAll(){
            
            $sqlSelect = $this->connection->query("SELECT * FROM $this->table");
            return $sqlSelect->fetchAll();
        }

        function getUserById($id)
        {
                $sqlSearchId = $this->connection->query("SELECT * FROM $this->table WHERE id = '$id'");
                return $sqlSearchId->fetchAll();
        }

        
        public function newUser($user){

            if (empty($user['name']) || empty($user['email'])) {
               return false;
            }
            try{
                $sqlNew = "INSERT INTO $this->table (name,email) VALUES (:name, :email)";
                $resultQuery = $this->connection->prepare($sqlNew)->execute(['name'=>$user['name'],'email'=>$user['email']]);
            }catch(exception $e){
                return $e;
            }
            return $this->check($resultQuery);
        }

        public function editUser($user)
        {
            if (empty($user['id']) || empty($user['name']) || empty($user['email'])) {
                return false;
            }

            try {
                $this->connection->beginTransaction();
                $sql = "UPDATE $this->table SET name = :name, email = :email WHERE id = :id";
                $stmt = $this->connection->prepare($sql);
                $resultQuery = $stmt->execute([
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'id' => $user['id']
                ]);

                if (!$resultQuery) {
                    $this->connection->rollBack();
                    return false;
                }

                $sqlDelete = "DELETE FROM user_colors WHERE user_id = :user_id";
                $stmtDelete = $this->connection->prepare($sqlDelete);
                $stmtDelete->execute(['user_id' => $user['id']]);

                if (!empty($user['colors']) && is_array($user['colors'])) {
                    $sqlInsert = "INSERT INTO user_colors (user_id, color_id) VALUES (:user_id, :color_id)";
                    $stmtInsert = $this->connection->prepare($sqlInsert);

                    foreach ($user['colors'] as $colorId) {
                        $stmtInsert->execute([
                            'user_id' => $user['id'],
                            'color_id' => $colorId
                        ]);
                    }
                }

                $this->connection->commit();
                return $this->check($resultQuery);

            } catch (Exception $e) {
                $this->connection->rollBack();
                return $e;
            }
        }

        function getUserByEmail($email){
            $sqlSearchEmail = $this->connection->query("SELECT * FROM $this->table WHERE email = '$email'");
            return $sqlSearchEmail->fetchAll();
        }
        

        function deleteUser($user)
        {
            $id = $user['id'] ?? null;

            if (empty($id) || !is_numeric($id) || (int)$id <= 0) {
                return false;
            }

            if (empty($this->getUserById($id))) {
                return false;
            }

            $sqlDelete = "DELETE FROM $this->table WHERE id = :id";
            $stmt = $this->getConnection()->prepare($sqlDelete);
            $result = $stmt->execute([':id' => $id]);

            return $this->check($result);
        }

        public function check($result){
            if($result == 1)
            {
                return true;
            }
            return false;
        }
    }
?>