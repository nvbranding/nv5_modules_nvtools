<!-- BEGIN: main -->
<div class="panel" id="themecopypre">
	<h2 class="text-center">{LANG.themecopy_note}</h2>
	<div class="text-center">
		<div class="dropdown theme-select-button">
			<button class="btn btn-info dropdown-toggle btn-lg" type="button" id="themeSelect" data-toggle="dropdown" aria-expanded="true">
				{LANG.themecopy_select}
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu theme-select-list" role="menu" aria-labelledby="themeSelect">
				<!-- BEGIN: theme --><li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="themeitem" rel="{THEME}">{THEME}</a></li><!-- END: theme -->
			</ul>
		</div>
	</div>
</div>
<div class="panel" id="themecopydata">

</div>
<div class="panel" id="themecopyload">
	<div class="text-center">
		<em class="fa fa-5x fa-spinner fa-pulse"></em>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.themeitem').click(function(e){
		e.preventDefault();
		$('#themecopypre').hide();
		$('#themecopyload').show();
		$.get(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&theme=' + $(this).attr('rel'), function(e){
			$('#themecopyload').hide();
			$('#themecopydata').hide().html(e).slideDown('slow');
		});
	})
});
</script>
<!-- END: main -->

<!-- BEGIN: theme -->
<div id="copyConfig">
	<h1 class="page-header">{LANG.themecopy_layout} <a href="#" data-callback="controlDefaultLayout" data-all="false" data-toggle="chooseAll" data-target="[name='layouts[]']" class="label label-primary text-white">{LANG.chooseAll}</a></h1>
	<div class="row">
		<!-- BEGIN: layout -->
		<div class="col-xs-6">
			<div class="text-center">
				<label for="layout-{LAYOUT}">
					<div class="layout-icon layout-{LAYOUT}"></div>
				</label>
				<p><input type="checkbox" name="layouts[]" value="{LAYOUT}" id="layout-{LAYOUT}"/></p>
				<p>{LAYOUT}</p>
			</div>
		</div>
		<!-- END: layout -->
	</div>
	<h1 class="page-header">{LANG.themecopy_defaultlayout}</h1>
    <div class="form-horizontal">
        <div class="form-group">
            <div class="col-sm-24">
                <select class="form-control" name="layoutdefault" data-changed="false" data-selected="{DEFAULT_LAYOUT}"></select>
            </div>
        </div>
    </div>
	<h1 class="page-header">{LANG.themecopy_module} <a href="#" data-all="false" data-toggle="chooseAll" data-target="[name='allmodules[]']" class="label label-primary text-white">{LANG.chooseAll}</a></h1>
	<!-- BEGIN: module -->
	<div class="module-item">
		<h3>
			<input class="mitem-all" type="checkbox" name="allmodules[]" value="{MODULE}" id="allmodule-{MODULE}"/>
			<label for="allmodule-{MODULE}">{MODULE}</label>
		</h3>
		<div class="module-data"></div>
	</div>
	<!-- END: module -->
	<h1 class="page-header">{LANG.themecopy_block} <a href="#" data-all="false" data-toggle="chooseAll" data-target="[name='allblocks[]']" class="label label-primary text-white">{LANG.chooseAll}</a></h1>
	<!-- BEGIN: block -->
	<div class="module-item">
		<h3>
			<input class="mitem-all" type="checkbox" name="allblocks[]" value="{BLOCK}" id="allblock-{BLOCK}"/>
			<label for="allblock-{BLOCK}">{BLOCK}</label>
		</h3>
		<div class="module-data"></div>
	</div>
	<!-- END: block -->
	<h1 class="page-header">{LANG.themecopy_select_other}</h1>
	<div class="form-horizontal">
		<div class="form-group">
			<label for="" class="control-label col-sm-8">{LANG.themecopy_new}</label>
			<div class="col-sm-16">
				<input class="form-control" type="text" name="newthemename" value=""/>
			</div>
		</div>
	</div>
	<div class="text-center">
		<button type="button" class="btn btn-lg btn-primary" id="copyStart">{LANG.themecopy_start}</button>
	</div>
	<input type="hidden" name="theme" value="{THEME}"/>
</div>

<script type="text/javascript">
function controlDefaultLayout() {
    var selLO = $('[name="layoutdefault"]');
    var op = '';
	$.each($('[name="layouts[]"]:checked'), function(){
        var lo = $(this).val();
		op += '<option value="' + lo + '"' + ((lo == selLO.data('selected') && selLO.data('changed') == false) ? ' selected="selected"' : '') +'>' + lo + '</option>'
	});
    selLO.html(op);
    selLO.data('changed', true);
}
$(document).ready(function(){
	$('#themecopydata').delegate( "#copyStart", "click", function(e){
		if( $('[name="layouts[]"]:checked').length == 0 ){
			alert('{LANG.themecopy_layout_warnning}');
			return;
		}
		
		var theme = $('[name="theme"]').val();
		var layoutdefault = $('[name="layoutdefault"]').val();
		var newthemename = $('[name="newthemename"]').val();
		var layouts = new Array();
		var modules = new Array();
		var blocks = new Array();
		
		$.each($('[name="layouts[]"]:checked'), function(){
			layouts.push($(this).val());
		});
		$.each($('[name="allmodules[]"]:checked'), function(){
			modules.push($(this).val());
		});
		$.each($('[name="allblocks[]"]:checked'), function(){
			blocks.push($(this).val());
		});
		
		layouts = layouts.join('|');
		modules = modules.join('|');
		blocks = blocks.join('|');
		
		if( newthemename == '' ){
			alert('{LANG.themecopy_new_warnning}');
			return;
		}
		
		$('#copyStart').hide();
		$('#themecopydata').append('<div id="themeCopyResult" class="well"><span class="text-danger">{LANG.themecopy_waiting}</span></div>');

		$.post(
			nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=themecopy&nocache=' + new Date().getTime(),
			'theme=' + theme + '&newthemename=' + newthemename + '&layoutdefault=' + layoutdefault + '&layouts=' + layouts + '&modules=' + modules + '&blocks=' + blocks, 
			function(res) {
				res = res.split('|');
				if(res[0] == 'ERR'){
					$('#themeCopyResult').html('<p><span class="text-danger">' + res[1] + '</span></p><p><a href="#" id="recopytheme">{LANG.themecopy_recopy}</a></p>');
				}else{
					$('#themeCopyResult').html('<p><span class="text-success"><a href="' + res[1] + '">{LANG.themecopy_success}</a></span></p>');
				}
			}
		);
	});
	
	$('#themecopydata').delegate( "#recopytheme", "click", function(e){
		e.preventDefault();
		$('#themeCopyResult').remove();
		$('#copyStart').show();
	});
	$('#themecopydata').delegate( '[name="layouts[]"]', "change", function(e){
		controlDefaultLayout();
	});
});
</script>
<!-- END: theme -->