<?php

require_once("class.Quartz.php");

// Get Config
$Quartz = new Quartz(True, True);


include("_header.php");
?>

<div class="wrapper">
	<div class="section">
		<h2>Personal</h2>
		<hr class="soften">
		<table class="g70 center">
			<tr>
				<td><input type="text" name="sectiontitle" placeholder="Section Title"></td>
			</tr>
			<tr>
				<td><textarea name="sectioncontent" placeholder="Section Content"></textarea></td>
			</tr>
			<tr>
				<td><a class="add">+ Add Section</a></td>
			</tr>
		</table>
	</div>

	<div class="section">
		<h2>Courses</h2>
		<hr class="soften">
		<table class="g70 center">
			<tr>
				<td><input type="text" name="sectiontitle" placeholder="Course Title"></td>
			</tr>
			<tr>
				<td>HMMMMMMM</td>
			</tr>
			<tr>
				<td><a class="add">+ Add Course</a></td>
			</tr>
		</table>
	</div>
</div>
<?
	include("_footer.php");
?>