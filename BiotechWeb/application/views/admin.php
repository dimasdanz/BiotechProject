<div id="page-wrapper">
	<div class="row">
		<div class="col-sm-12">
			<h1 class="page-header">Admin</h1>
		</div>
	</div>
	<?php 
		if($this->session->flashdata('success')){
	?>
		<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<?=$this->session->flashdata('success')?>
		</div>
    <?php 
    	}
    ?>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-hover" id="dataTables-admin">
							<thead>
								<tr>
									<th width="5%">No.</th>
									<th>Name</th>
									<th>Action</th>
								</tr>
							</thead>
							<?php
								$no = 1;
								if($admin != NULL){
									foreach ($admin as $row){
							?>
							<tbody>
								<tr>
									<td><?=$no?></td>
									<td><?=$row->username?></td>
									<td><button data-toggle="modal" data-target="#modal_edit" class="btn btn-success edit" id="<?=$row->username?>"><i class="fa fa-edit"></i> Edit</button></td>
								</tr>
							</tbody>
							<?php 
									$no++;
									}
								}
							?>
						</table>
					</div>
				</div>
				<div class="panel-footer clearfix">
					<button class="btn btn-primary pull-right" data-toggle="modal" data-target="#modal_add">Add New Admin</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?=form_open('/admin/insert')?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Form Admin</h4>
				</div>
				<div class="modal-body">
					<?php
						if($this->session->flashdata('error')){
							$username = $this->session->flashdata('error')[0];
							$error = $this->session->flashdata('error')[1];
					?>
					<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<?=$error?>
					</div>
					<?php
						}else{
							$username = "";
						}
					?>
					<div class="form-group">
						<label>Username</label>
						<input class="form-control" placeholder="Username" type="text" name="username" value="<?=$username?>" required>
					</div>
					<div class="form-group">
						<label>Password</label>
						<input class="form-control" placeholder="Password" type="password" name="password" required>
					</div>
					<div class="form-group">
						<label>Confirm Password</label>
						<input class="form-control" placeholder="Confirm Password" type="password" name="confirm_password" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Add New Admin</button>
				</div>
			<?=form_close()?>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_edit" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-left">Edit Admin</h4>
            </div>
            <div class="modal-body">
            	<div class="row">
            		<?php
						if($this->session->flashdata('delete_error')){
					?>
					<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<?=$this->session->flashdata('delete_error')?>
					</div>
					<?php
						}
					?>
            		<div class="col-sm-offset-3">
            		<?=form_open('/admin/reset_password')?>
	            		<input type="hidden" name="username" id="username">
						<button type="submit" class="btn btn-default"><i class="fa fa-refresh"></i> Reset Password</button>
						<a href="#" class="btn btn-danger delete" data-toggle="modal" data-target="#modal_confirm" id="delete"><i class="fa fa-trash-o"></i> Delete This Account</a>
					<?=form_close()?>										
				</div>
			</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_confirm" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-left">Confirmation</h4>
            </div>
            <div class="modal-body">
                <p id="confirm_str"></p>
            </div>
            <div class="modal-footer">
            	<?=form_open("/admin/delete")?>
	            	<input type="hidden" name="username" id="user">
	                <button type="submit" class="btn btn-success" id="yes">Yes</button>
	                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                <?=form_close()?>
            </div>
        </div>
    </div>
</div>
<?php
	$this->load->view('template/footer');
?>
<script src="<?=base_url()?>assets/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="<?=base_url()?>assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script>
	$(document).ready(function(){
		<?php
			if($this->session->flashdata('error')){
		?>
			$('#modal_add').modal('show');
		<?php
			}
		?>
		<?php
			if($this->session->flashdata('delete_error')){
		?>
			$('#modal_edit').modal('show');
		<?php
			}
		?>
		$('#dataTables-admin').dataTable();

		$(".edit").click(function() {
			var id = this.id;
			var logged_in = '<?=$this->session->userdata('logged_in')?>';
			var username = new String(id);
			$("#delete").removeAttr('disabled');
			if(username == logged_in){
				$("#delete").attr('disabled','disabled');
			}
			$("#username").val(id);
		});
		
		$(".delete").click(function() {
			var username = $("#username").val();
			$("#user").val(username);
			$("#confirm_str").html('Are you sure want to delete <b>'+username+'</b>?');
		});
	});
</script>
