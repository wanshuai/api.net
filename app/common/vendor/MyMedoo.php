<?php
    
    namespace vendor;
    
    use PDO;
    use Exception;
    use InvalidArgumentException;
	use Medoo\Medoo;

    class MyMedoo extends Medoo {

        // 修改function以适应有参数的情况
        public function actionWithParams($actions, $params=null)
        {
            if (is_callable($actions))
            {
                $this->pdo->beginTransaction();

                try {
                    if($params === null){
                        $result = $actions($this);
                    }else{
                        $result = $actions($this, $params);
                    }
                    

                    if ($result === false)
                    {
                        $this->pdo->rollBack();
                    }
                    else
                    {
                        $this->pdo->commit();
                    }
                }
                catch (Exception $e) {
                    $this->pdo->rollBack();

                    throw $e;
                }

                return $result;
            }

            return false;
        }
        
    }