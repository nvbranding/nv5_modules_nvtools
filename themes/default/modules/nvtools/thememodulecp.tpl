<!-- BEGIN: main -->
<div class="panel">
	<h2 class="text-center">{LANG.thememdcp}</h2>
	<div class="alert alert-info">
		{LANG.thememdcp_note}
	</div>
	<div class="text-center">
		<div id="contentdata">
			<div class="dropdown theme-select-button">
				<button class="btn btn-info dropdown-toggle" type="button" id="fthemeSelect" data-toggle="dropdown" aria-expanded="true">
					{LANG.thememdcp_select_theme}
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu theme-select-list" role="menu" aria-labelledby="fthemeSelect">
					<!-- BEGIN: theme --><li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="themeitem clickitem" rel="{THEME}">{THEME}</a></li><!-- END: theme -->
				</ul>
			</div>
		</div>
		<div class="panel" id="contentajax">
		
		</div>
		<div class="panel" id="contentsubmit">
			<input type="button" class="btn btn-info" name="submit" value="{LANG.thememdcp_submit}"/>
		</div>
		<div class="panel" id="contentloader">
			<div class="text-center">
				<em class="fa fa-lg fa-spinner fa-pulse"></em>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var fromtheme, cpmodule, totheme = '';
$(document).ready(function(){
	$('.themeitem').click(function(e){
		e.preventDefault();
		$('#contentloader').css('display', 'inline-block');
		$('#contentajax').html('');
		fromtheme = $(this).attr('rel');
		cpmodule = totheme = '';
		$('#fthemeSelect').html(fromtheme + ' <span class="caret"></span>');
		$.get(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&fromtheme=' + fromtheme, function(e){
			$('#contentajax').html(e);
			$('#contentloader').css('display', 'none');
		});
	})

	$('body').delegate( ".cpmoduleitem", "click", function(e){
		e.preventDefault();
		cpmodule = $(this).attr('rel');
		$('#cpmodule').html(cpmodule + ' <span class="caret"></span>');
	});

	$('body').delegate( ".tthemeitem", "click", function(e){
		e.preventDefault();
		totheme = $(this).attr('rel');
		$('#tthemeSelect').html(totheme + ' <span class="caret"></span>');
	});

	$('body').delegate( ".clickitem", "click", function(e){
		e.preventDefault();
		if( fromtheme != '' && cpmodule != '' && totheme != '' ){
			$('#contentsubmit').css('display', 'inline-block');
		}else{
			$('#contentsubmit').css('display', 'none');
		}
	});

	$('body').delegate( '[name="submit"]', "click", function(e){
		e.preventDefault();
		$('#contentloader').css('display', 'inline-block');
		$.post(
			nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&nocache=' + new Date().getTime(),
			'fromtheme=' + fromtheme + '&cpmodule=' + cpmodule + '&totheme=' + totheme, 
			function(res) {
				$('#contentloader').css('display', 'none');
				res = res.split('|');
				if(res[0] == 'ERR'){
					alert( res[1] );
				}else{
					alert('{LANG.thememdcp_success}');
					window.location.href = window.location.href;
				}
			}
		);
	});
});
</script>
<!-- END: main -->

<!-- BEGIN: cpmodule -->
<div class="dropdown theme-select-button">
	<button class="btn btn-info dropdown-toggle" type="button" id="cpmodule" data-toggle="dropdown" aria-expanded="true">
		{LANG.thememdcp_select_module}
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu theme-select-list" role="menu" aria-labelledby="cpmodule">
		<!-- BEGIN: module --><li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="cpmoduleitem clickitem" rel="{MODULE}">{MODULE}</a></li><!-- END: module -->
	</ul>
</div>
<div class="dropdown theme-select-button">
	<button class="btn btn-info dropdown-toggle" type="button" id="tthemeSelect" data-toggle="dropdown" aria-expanded="true">
		{LANG.thememdcp_select_ttheme}
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu theme-select-list" role="menu" aria-labelledby="tthemeSelect">
		<!-- BEGIN: theme --><li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="tthemeitem clickitem" rel="{THEME}">{THEME}</a></li><!-- END: theme -->
	</ul>
</div>
<!-- END: cpmodule -->