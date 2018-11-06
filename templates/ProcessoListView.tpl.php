<?php
	$this->assign('title','Gerenciamentos de Processos | Processos');
	$this->assign('nav','processos');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/processos.js").wait(function(){
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
	<img src="./images/logo-iterj-min.png" width="50px" height="50px" alt="logo-iterj"/> Processos
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Pesquisar..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>
   
	<!-- underscore template for the collection -->
	<script type="text/template" id="processoCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_Id">Id<% if (page.orderBy == 'Id') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_DataEntrada" style="width:100px;">Data Entrada<% if (page.orderBy == 'DataEntrada') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Numero" style="width:120px;">Numero<% if (page.orderBy == 'Numero') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Nome">Nome<% if (page.orderBy == 'Nome') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Assunto">Assunto<% if (page.orderBy == 'Assunto') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>

				<th id="header_DataSaida" style="width:100px;">Data Saida<% if (page.orderBy == 'DataSaida') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th> 
				<th id="header_Observacao">Observacao<% if (page.orderBy == 'Observacao') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_SetorId">Setor<% if (page.orderBy == 'SetorId') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>

			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('id')) %>">
				<td><%= _.escape(item.get('id') || '') %></td>
				<td><%if (item.get('dataEntrada')) { %><%= _date(app.parseDate(item.get('dataEntrada'))).format('D/MM/YYYY') %><% } else { %>NULL<% } %></td>
				<td><%= _.escape(item.get('numero') || '') %></td>
				<td><%= _.escape(item.get('nome') || '') %></td>
				<td><%= _.escape(item.get('assunto') || '') %></td>
                                <!--Linha original -->    
				<!--<td><%if (item.get('dataSaida')) { %><%= _date(app.parseDate(item.get('dataSaida'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></td>-->
                                <td><%if (item.get('dataSaida')!='1970-01-01') { %><%= _date(app.parseDate(item.get('dataSaida'))).format('D/MM/YYYY') %><% } else { %><% } %></td> 
				<td><%= _.escape(item.get('observacao') || '') %></td>
				<td><%= _.escape(item.get('setor') || '') %></td>

			</tr>
		<% }); %>
		</tbody>
		</table>
              
		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="processoModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
                                <!--    
				<div id="idInputContainer" class="control-group">
					<label class="control-label" for="id">Id</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="id"><%= _.escape(item.get('id') || '') %></span>
						<span class="help-inline"></span>
					</div>
				</div>
                                -->
				<div id="dataEntradaInputContainer" class="control-group">
					<label class="control-label" for="dataEntrada">Data Entrada</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="dd-mm-yyyy">
							<input id="dataEntrada" type="text" value="<%= _date(app.parseDate(item.get('dataEntrada'))).format('DD-MM-YYYY') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="numeroInputContainer" class="control-group">
					<label class="control-label" for="numero">Numero</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="numero" placeholder="Numero" value="<%= _.escape(item.get('numero') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="nomeInputContainer" class="control-group">
					<label class="control-label" for="nome">Nome</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="nome" placeholder="Nome" value="<%= _.escape(item.get('nome') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="assuntoInputContainer" class="control-group">
					<label class="control-label" for="assunto">Assunto</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="assunto" placeholder="Assunto" value="<%= _.escape(item.get('assunto') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="dataSaidaInputContainer" class="control-group">
					<label class="control-label" for="dataSaida">Data Saida</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="dd-mm-yyyy">
                                                    <!--Linha original -->
                                                    <!--<input id="dataSaida" type="text" value="<%= _date(app.parseDate(item.get('dataSaida'))).format('YYYY-MM-DD') %>" />   -->  
                                                    <input id="dataSaida" type="text" value="<%if (item.get('dataSaida')!= '1970-01-01') { %><%= _date(app.parseDate(item.get('dataSaida'))).format('DD-MM-YYYY') %><% } else { %>""<% } %>" />
                                                   
                                                    <span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="observacaoInputContainer" class="control-group">
					<label class="control-label" for="observacao">Observacao</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="observacao" placeholder="Observacao" value="<%= _.escape(item.get('observacao') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="setorIdInputContainer" class="control-group">
					<label class="control-label" for="setorId">Setor</label>
					<div class="controls inline-inputs">
						<select id="setorId" name="setorId"></select>
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteProcessoButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteProcessoButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Excluir Processo</button>
						<span id="confirmDeleteProcessoContainer" class="hide">
							<button id="cancelDeleteProcessoButton" class="btn btn-mini">Cancelar</button>
							<button id="confirmDeleteProcessoButton" class="btn btn-mini btn-danger">Confirmar</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="processoDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i>Editar Processo
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="processoModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancelar</button>
			<button id="saveProcessoButton" class="btn btn-primary">Salvar Mudanças</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="processoCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newProcessoButton" class="btn btn-primary">Adicionar Processo</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
