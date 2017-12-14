<style>
table, thead, td, th, tr {
border-collapse: collapse;
    text-align: center;
    font-size:94%;
}

tbody,thead, td, th, tr
{
border: solid 1px black; 
}

table {
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
}

.jsdragtable-contents {
    background: #fff;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    box-shadow: 2px 2px 5px #aaa;
    padding: 0;
}

.jsdragtable-contents table {
    margin-bottom: 0;
}
</style>

<script>
    ///<reference path="../typings/jquery/jquery.d.ts"/>
    var Anterec;
    (function (Anterec) {
        var JsDragTable = (function () {
            function JsDragTable(target) {
                this.offsetX = 20;
                this.offsetY = 20;
                this.container = target;
                this.rebind();
            }
            JsDragTable.prototype.rebind = function () {
                var _this = this;
                $(this.container).find("th").each(function (headerIndex, header) {
                    $(header).off("mousedown touchstart");
                    $(header).off("mouseup touchend");
                    $(header).on("mousedown touchstart", function (event) {
                        _this.selectColumn($(header), event);
                    });
                    $(header).on("mouseup touchend", function (event) {
                        _this.dropColumn($(header), event);
                    });
                });
                $(this.container).on("mouseup touchend", function () {
                    _this.cancelColumn();
                });
            };

            JsDragTable.prototype.selectColumn = function (header, event) {
                var _this = this;
                event.preventDefault();
                var userEvent = new UserEvent(event);
                this.selectedHeader = header;
                var sourceIndex = this.selectedHeader.index() + 1;
                var cells = [];

                $(this.container).find("tr td:nth-child(" + sourceIndex + ")").each(function (cellIndex, cell) {
                    cells[cells.length] = cell;
                });

                this.draggableContainer = $("<div/>");
                this.draggableContainer.addClass("jsdragtable-contents");
                this.draggableContainer.css({ position: "absolute", left: userEvent.event.pageX + this.offsetX, top: userEvent.event.pageY + this.offsetY });

                var dragtable = this.createDraggableTable(header);

                $(cells).each(function (cellIndex, cell) {
                    var tr = $("<tr/>");
                    var td = $("<td/>");
                    $(td).html($(cells[cellIndex]).html());
                    $(tr).append(td);
                    $(dragtable).find("tbody").append(tr);
                });

                this.draggableContainer.append(dragtable);
                $("body").append(this.draggableContainer);
                $(this.container).on("mousemove touchmove", function (event) {
                    _this.moveColumn($(header), event);
                });
                $(".jsdragtable-contents").on("mouseup touchend", function () {
                    _this.cancelColumn();
                });
            };

            JsDragTable.prototype.moveColumn = function (header, event) {
                event.preventDefault();
                if (this.selectedHeader !== null) {
                    var userEvent = new UserEvent(event);
                    this.draggableContainer.css({ left: userEvent.event.pageX + this.offsetX, top: userEvent.event.pageY + this.offsetY });
                }
            };

            JsDragTable.prototype.dropColumn = function (header, event) {
                var _this = this;
                event.preventDefault();
                var sourceIndex = this.selectedHeader.index() + 1;
                var targetIndex = $(event.target).index() + 1;
                var tableColumns = $(this.container).find("th").length;

                var userEvent = new UserEvent(event);
                if (userEvent.isTouchEvent) {
                    header = $(document.elementFromPoint(userEvent.event.clientX, userEvent.event.clientY));
                    targetIndex = $(header).prevAll().length + 1;
                }

                if (sourceIndex !== targetIndex) {
                    var cells = [];
                    $(this.container).find("tr td:nth-child(" + sourceIndex + ")").each(function (cellIndex, cell) {
                        cells[cells.length] = cell;
                        $(cell).remove();
                        $(_this.selectedHeader).remove();
                    });

                    if (targetIndex >= tableColumns) {
                        targetIndex = tableColumns - 1;
                        this.insertCells(cells, targetIndex, function (cell, element) {
                            $(cell).after(element);
                        });
                    } else {
                        this.insertCells(cells, targetIndex, function (cell, element) {
                            $(cell).before(element);
                        });
                    }

                    $(this.container).off("mousemove touchmove");
                    $(".jsdragtable-contents").remove();
                    this.draggableContainer = null;
                    this.selectedHeader = null;
                    this.rebind();
                }
            };

            JsDragTable.prototype.cancelColumn = function () {
                $(this.container).off("mousemove touchmove");
                $(".jsdragtable-contents").remove();
                this.draggableContainer = null;
                this.selectedHeader = null;
            };

            JsDragTable.prototype.createDraggableTable = function (header) {
                var table = $("<table/>");
                var thead = $("<thead/>");
                var tbody = $("<tbody/>");
                var tr = $("<tr/>");
                var th = $("<th/>");
                $(table).addClass($(this.container).attr("class"));
                $(table).width($(header).width());
                $(th).html($(header).html());
                $(tr).append(th);
                $(thead).append(tr);
                $(table).append(thead);
                $(table).append(tbody);
                return table;
            };

            JsDragTable.prototype.insertCells = function (cells, columnIndex, callback) {
                var _this = this;
                $(this.container).find("tr td:nth-child(" + columnIndex + ")").each(function (cellIndex, cell) {
                    callback(cell, $(cells[cellIndex]));
                });
                $(this.container).find("th:nth-child(" + columnIndex + ")").each(function (cellIndex, cell) {
                    callback(cell, $(_this.selectedHeader));
                });
            };
            return JsDragTable;
        })();
        Anterec.JsDragTable = JsDragTable;

        var UserEvent = (function () {
            function UserEvent(event) {
                this.event = event;
                if (event.originalEvent && event.originalEvent.touches && event.originalEvent.changedTouches) {
                    this.event = event.originalEvent.touches[0] || event.originalEvent.changedTouches[0];
                    this.isTouchEvent = true;
                }
            }
            return UserEvent;
        })();
    })(Anterec || (Anterec = {}));
    jQuery.fn.extend({
        jsdragtable: function () {
            return new Anterec.JsDragTable(this);
        }
    });


</script>

<?php 
$global_arr = array();
$academic_year = "";
$id = "";
$exam_title = "";

$student_array_data = unserialize(base64_decode(get_session_data()['class_report']));
$this->session->unset_userdata('class_report');

$global_arr = get_student_by_id($student_array_data[0]['id']);
$stud_school_name = get_session_data()['profile']['partner_name'];


$month = 0;
$year = 0;
foreach($student_array_data[0]['exam_info']['exams'] as $key => $all_exam)
{  
    if($key==0)
    {
        if($all_exam['month']<6)
        {
        $month = $all_exam['month'];
        $year = $all_exam['end_year'];
        }
        else
        {
        $month = $all_exam['month'];
        $year = $all_exam['start_year'];
        }
    }

   
    $new_year;
    if($all_exam['month']<6)
    {
        $new_year = $all_exam['end_year'];
    }
    else
    {
        $new_year = $all_exam['start_year'];
    }
    
    if($new_year>$year)
    {
        $month = $all_exam['month'];
        $year = $new_year;
    }
}

$month_arr[1] = "Jan";
$month_arr[2] = "Feb";
$month_arr[3] = "March";
$month_arr[4] = "April";
$month_arr[5] = "May";
$month_arr[6] = "June";
$month_arr[7] = "July";
$month_arr[8] = "August";
$month_arr[9] = "Sept";
$month_arr[10] = "Oct";
$month_arr[11] = "Nov";
$month_arr[12] = "Dec";


$std_check=array(9,10);
$stud_class=$class_name[0]['standard'];


if(!in_array($stud_class,$std_check)) 
{

function check_grade($marks)
{
    $grade ;
    if($marks <= 20)                        {$grade = 'E-2';   return $grade; }
    elseif($marks >= 21 && $marks <= 32)    {$grade = 'E-1';   return $grade; }
    elseif($marks >= 33 && $marks <= 40)    {$grade = 'D';     return $grade; }
    elseif($marks >= 41 && $marks <= 50)    {$grade = 'C-2';   return $grade; }
    elseif($marks >= 51 && $marks <= 60)    {$grade = 'C-1';   return $grade; }
    elseif($marks >= 61 && $marks <= 70)    {$grade = 'B-2';   return $grade; }
    elseif($marks >= 71 && $marks <= 80)    {$grade = 'B-1';   return $grade; }
    elseif($marks >= 81 && $marks <= 90)    {$grade = 'A-2';   return $grade; }
    elseif($marks >= 91 && $marks <= 100)   {$grade = 'A-1';   return $grade; }
}



?>



<section style="margin-top:40px;text-align:center;" onload="setTimeout(myFunction(), 3000)">
<div class="class_report_div container">

<br>

<table style="width:auto; border:1px solid black; border:1;" border="1px">

<?php

$array_data = $student_array_data[0]['exam_info'];


// Displaying all headers/headings... 
    echo '<th>' . "Sr. No." . '</th>';
    echo '<th>' . "Roll No." . '</th>';
    echo '<th>' . "Name of the Student" . '</th>';
    foreach($array_data['data'] as $key => $value)
    {
        if(strlen($value['subject_name'])>9)
         {
        echo '<th style:"word-wrap:break-word";>' . $value['subject_name'] . '</th>';
         }
         else {
           echo '<th class="nowrap">' . $value['subject_name'] . '</th>'; 
         }
    }
  
    echo '<th style:"word-wrap:break-word"; >'. 'No. of passing subjects' .'</th>';
    echo '<th>'. 'Remarks' .'</th>';

//Displaying student names and marks
    $sr_no = 1;
   foreach($student_array_data as $k => $v)       
    {
        echo '<tr>';

        $grand_total = 0;
        echo '<td>'. $sr_no . '</td>';
        $sr_no++;
        echo '<td>'. $v['rollno'].'</td>';
        echo '<td>'. $v['last_name']. ' '.
        $v['first_name']. ' '.
        $v['middle_name'].'</td>';
                
        $max_marks=0;
        $no_of_passing_subjects=0;
        $no_of_subjects=0;

        foreach($v['exam_info']['data'] as $key => $marks)     
        {
            $total_obtained_marks=0;
            $total_passing =0;
            $onesubject_total_marks=0;        
            
            foreach ($marks['exam'] as $k1 => $v1)
            {
                $v1['marks_obtained'] = (($v1['marks_obtained']) =='-') ? 0 : $v1['marks_obtained'];
                $total_obtained_marks += $v1['marks_obtained'];

                $v1['passing_marks'] = (($v1['passing_marks']) =='-') ? 0 : $v1['passing_marks'];
                $total_passing += $v1['passing_marks'];

                $v1['total_marks'] = (($v1['total_marks']) =='-') ? 0 : $v1['total_marks'];
                $onesubject_total_marks += $v1['total_marks'];            
            }

            $no_of_subjects++;
            if($total_obtained_marks >= $total_passing)  //condition to check in how many subjects student is passing
            {            $no_of_passing_subjects++;        }
        
            if($total_obtained_marks < $total_passing)
            {echo "<td style='background-color:lightgrey'>" .check_grade(($total_obtained_marks*100)/$onesubject_total_marks). '</td>';} // marks obtained in one subject including all exams
            else
            {
            echo '<td>' .check_grade(($total_obtained_marks*100)/$onesubject_total_marks). '</td>';
            }

            $grand_total += $total_obtained_marks;          // adding marks obtained by student in all subjects
            $max_marks += $onesubject_total_marks;          // adding total_marks of all subjects
        }

       $percent = round((($grand_total*100) / $max_marks),2) ;
       

        if($no_of_subjects == $no_of_passing_subjects)
        {echo '<td>' .$no_of_subjects . '</td>';}
        else{
        echo '<td style="padding:0px 2px;">' .$no_of_passing_subjects. '</td>';
        }
        echo '<td>' .' '. '</td>';
            
        echo '</tr>';
    }
}

else
{

?>

<section style="margin-top:40px;text-align:center;" onload="setTimeout(myFunction(), 3000)">
<div class="class_report_div">

<br>

<table style="width:auto; border:1px solid black; border:1;" border="1px">

<?php

$array_data = $student_array_data[0]['exam_info'];


// Displaying all headers/headings... 
    echo '<th>' . "Sr. No." . '</th>';
    echo '<th>' . "Roll No." . '</th>';
    echo '<th>' . "Name of the Student" . '</th>';
    foreach($array_data['data'] as $key => $value)
    {
        if(strlen($value['subject_name'])>9)
         {
        echo '<th style:"word-wrap:break-word";>' . $value['subject_name'] . '</th>';
         }
         else {
           echo '<th class="nowrap">' . $value['subject_name'] . '</th>'; 
         }
    }
    echo '<th style:"word-wrap:break-word";>' . 'Grand Total' . '</th>';
    echo '<th>' . '%' . '</th>';
    echo '<th style:"word-wrap:break-word"; >'. 'No. of passing subjects' .'</th>';
    echo '<th>'. 'Remarks' .'</th>';


// Row to print Max_marks in each subject
    $grand_total=0;
    echo "<tr><td></td><td></td><td>Max Marks</td>";
    foreach($array_data['data'] as $k => $v)    
    { 
       
        $total_obtained_marks=0;
        $total_passing =0;
        $onesubject_total_marks=0;
       
    
        foreach ($v['exam'] as $k1 => $v1)
        {
            $v1['total_marks'] = (($v1['total_marks']) =='-') ? 0 : $v1['total_marks'];
            $onesubject_total_marks += $v1['total_marks'];
        }
        
        echo "<td>".$onesubject_total_marks ."</td>";
        $grand_total +=$onesubject_total_marks;
    }
    
    echo "<td>".$grand_total."</td><td></td><td></td><td></td></tr>";
// Max_marks row ends 


//Displaying student names and marks
    $sr_no = 1;
   foreach($student_array_data as $k => $v)       
    {
        echo '<tr>';

        $grand_total = 0;
        echo '<td>'. $sr_no . '</td>';
        $sr_no++;
        echo '<td>'. $v['rollno'].'</td>';
        echo '<td>'. $v['last_name']. ' '.
        $v['first_name']. ' '.
        $v['middle_name'].'</td>';
                
        $max_marks=0;
        $no_of_passing_subjects=0;
        $no_of_subjects=0;

        foreach($v['exam_info']['data'] as $key => $marks)     
        {
            $total_obtained_marks=0;
            $total_passing =0;
            $onesubject_total_marks=0;        
            
            foreach ($marks['exam'] as $k1 => $v1)
            {
                $v1['marks_obtained'] = (($v1['marks_obtained']) =='-') ? 0 : $v1['marks_obtained'];
                $total_obtained_marks += $v1['marks_obtained'];

                $v1['passing_marks'] = (($v1['passing_marks']) =='-') ? 0 : $v1['passing_marks'];
                $total_passing += $v1['passing_marks'];

                $v1['total_marks'] = (($v1['total_marks']) =='-') ? 0 : $v1['total_marks'];
                $onesubject_total_marks += $v1['total_marks'];            
            }

            $no_of_subjects++;
            if($total_obtained_marks >= $total_passing)  //condition to check in how many subjects student is passing
            {            $no_of_passing_subjects++;        }
        
            if($total_obtained_marks < $total_passing)
            {echo "<td style='background-color:lightgrey'>" .$total_obtained_marks. '</td>';} // marks obtained in one subject including all exams
            else
            {
            echo '<td>' .$total_obtained_marks. '</td>';
            }

            $grand_total += $total_obtained_marks;          // adding marks obtained by student in all subjects
            $max_marks += $onesubject_total_marks;          // adding total_marks of all subjects
        }

        echo '<td>' .$grand_total."/".$max_marks. '</td>';  // total marks obtained in all subjects
        $percent = round((($grand_total*100) / $max_marks),2) ;
        echo '<td>' .$percent . '</td>';

        if($no_of_subjects == $no_of_passing_subjects)
        {echo '<td>' .$no_of_subjects . '</td>';}
        else{
        echo '<td style="padding:0px 2px;">' .$no_of_passing_subjects. '</td>';
        }
        echo '<td>' .' '. '</td>';
            
        echo '</tr>';
    }

}
?>

</table>
</div>
</section>
<script>
    $(document).ready(function() {
        $("table").jsdragtable();
    });
</script>
<footer class="page-break"></footer>

