<?php
include 'db.php';

if(isset($_POST['save'])){

$student=$_POST['student'];
$subject=$_POST['subject'];
$t1=$_POST['test1'];
$t2=$_POST['test2'];
$assignment=$_POST['assignment'];
$seminar=$_POST['seminar'];

// Direct ah 15 ku convert pannu
$t1_15 = ($t1 / 50) * 15;
$t2_15 = ($t2 / 50) * 15;

// Rendoda average
$best = ($t1_15 + $t2_15) / 2;

$total = $best + $assignment + $seminar;

$check=$conn->prepare("SELECT * FROM marks WHERE student_id=? AND sub_id=?");
$check->bind_param("ii",$student,$subject);
$check->execute();

$res=$check->get_result();

if($res->num_rows>0){

$sql=$conn->prepare("UPDATE marks SET
test1=?,test2=?,assignment=?,seminar=?,
test1_15=?,test2_15=?,best_test=?,total_internal=?
WHERE student_id=? AND sub_id=?");

$sql->bind_param("iiiiddddii",
$t1,$t2,$assignment,$seminar,
$t1_15,$t2_15,$best,$total,$student,$subject);

}else{

$sql=$conn->prepare("INSERT INTO marks
(student_id,sub_id,test1,test2,assignment,seminar,test1_15,test2_15,best_test,total_internal)
VALUES(?,?,?,?,?,?,?,?,?,?)");

$sql->bind_param("iiiiiddddd",
$student,$subject,$t1,$t2,$assignment,$seminar,$t1_15,$t2_15,$best,$total);

}

$sql->execute();

echo "Saved Successfully";
}
?>