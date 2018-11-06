<?php
	$this->assign('title','Gerenciamentos de Processos | Roles');
	$this->assign('nav','roles');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/roles.js").wait(function(){
		$(document).ready(function(){
			page.init();
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<div class="container">

<h1>
	<!--<i class="icon-th-list"></i>--><img src="./images/logo-iterj-min.png" width="50px" height="50px" alt="logo-iterj"/> Roles
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Pesquisar..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="roleCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_Id">Id<% if (page.orderBy == 'Id') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Role">Role<% if (page.orderBy == 'Role') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Created">Criado<% if (page.orderBy == 'Created') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Modified">Modificado<% if (page.orderBy == 'Modified') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('id')) %>">
				<td><%= _.escape(item.get('id') || '') %></td>
				<td><%= _.escape(item.get('role') || '') %></td>
				<td><%if (item.get('created')) { %><%= _date(app.parseDate(item.get('created'))).format('D/MM/YYYY h:mm A') %><% } else { %>NULL<% } %></td>
				<td><%if (item.get('modified')) { %><%= _date(app.parseDate(item.get('modified'))).format('D/MM/YYYY h:mm A') %><% } else { %>NULL<% } %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="roleModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="idInputContainer" class="control-group">
					<label class="control-label" for="id">Id</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="id"><%= _.escape(item.get('id') || '') %></span>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="roleInputContainer" class="control-group">
					<label class="control-label" for="role">Role</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="role" placeholder="Role" value="<%= _.escape(item.get('role') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="createdInputContainer" class="control-group">
					<label class="control-label" for="created">Created</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="created" type="text" value="<%= _date(app.parseDate(item.get('created'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<div class="input-append bootstrap-timepicker-component">
							<input id="created-time" type="text" class="timepicker-default input-small" value="<%= _date(app.parseDate(item.get('created'))).format('h:mm A') %>" />
							<span class="add-on"><i class="icon-time"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="modifiedInputContainer" class="control-group">
					<label class="control-label" for="modified">Modified</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="modified" type="text" value="<%= _date(app.parseDate(item.get('modified'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<div class="input-append bootstrap-timepicker-component">
							<input id="modified-time" type="text" class="timepicker-default input-small" value="<%= _date(app.parseDate(item.get('modified'))).format('h:mm A') %>" />
							<span class="add-on"><i class="icon-time"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteRoleButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteRoleButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Role</button>
						<span id="confirmDeleteRoleContainer" class="hide">
							<button id="cancelDeleteRoleButton" class="btn btn-mini">Cancel</button>
							<button id="confirmDeleteRoleButton" class="btn btn-mini btn-danger">Confirm</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="roleDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Editar Role
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="roleModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancelar</button>
			<button id="saveRoleButton" class="btn btn-primary">Salvar Mudanças</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="roleCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newRoleButton" class="btn btn-primary">Adicionar Role</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
