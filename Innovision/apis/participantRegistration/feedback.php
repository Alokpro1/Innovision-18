<?php
    include('../db.php');

    //$vars
    $liked = $_POST['liked'];
    $unliked = $_POST['disliked'];
    $ratings = $_POST['rating'];
    $suggestion = $_POST['suggestion'];
    $next_yr = $_POST['next_yr'];
    $token = $_POST['token'];

    
    if(isset($token)){
        $query0 = "SELECT * FROM users WHERE token ='".$token."'";
        $result1 = mysqli_query($conn,$query0);
        $row = mysqli_fetch_assoc($result1);
        if(mysqli_num_rows($result1) > 0)
        {
            if($row['checked_in'] == 1){
                $innoid = $row['inno_id'];
                $name = $row['name'];
                $college = $row['college'];

                $query = "INSERT INTO feedback(innoid,liked_event,disliked_event,ratings,suggestion,next_year) VALUES('".$innoid."','".$liked."','".$unliked."','".$ratings."','".$suggestion."','".$next_yr."')";
                if(mysqli_query($conn,$query)){
                            
                    //GENERATE CERTIFICATE
                    $output = '../../images/certificates/'.$innoid.'.jpg';
                    $image = imagecreatefromjpeg('../../images/certificates/original.jpg');
                    $font_color = imagecolorallocate($image, 0, 0, 0);
                    $text = imagettftext($image,15,0,80,180,$font_color,"../../fonts/Charmonman/Charmonman-Bold.ttf",$name);
                    $text1 = imagettftext($image,15,0,80,200,$font_color,"../../fonts/Charmonman/Charmonman-Bold.ttf",$college);
                    
                    if(imagejpeg($image,$output)){
                        $query1 = "UPDATE users SET feedback_status = 1 WHERE inno_id='".$innoid."'";
                        if(mysqli_query($conn,$query1)){
                            echo(json_encode(array('status' => 'success', 'message' => 'Feedback recieved')));
                        }
                        else{
                            echo(json_encode(array('status' => 'failure', 'message' => 'Feedback could not be recieved')));
                        }
                    }
                    else{
                        echo(json_encode(array('status' => 'failure', 'message' => 'Certificate couldnot be recieved')));
                    }
                        
                }
            else{
                echo(json_encode(array('status' => 'failure', 'message' => 'Db operaton failed !!!')));
            }
        }
        else{
            echo(json_encode(array('status' => 'failure', 'message' => 'You did not participate in inno')));
         }
    }
        else{
             echo(json_encode(array('status' => 'failure', 'message' => 'User Not Found')));
        }

        
    }
    else{
            echo(json_encode(array('status' => 'failure', 'message' => 'Unauthorized access')));
    }
     
   
?>