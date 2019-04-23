<!-- BEGIN: main -->
<div class="alert alert-warning" role="alert">Để nén file js, css của 1 site, Cần xuất các file css,js ra, sau đó copy file vào thư mục: uploads/{MODULE_NAME}/{OP} chương trình sẽ nén và ghi đè các file này.</div>
<form action="{NV_BASE_SITEURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post">
	<div style="text-align: center">
		<input name="submit" type="submit" value="Thực hiện" />
	</div>
</form>
<!-- BEGIN: js -->
<table class="table table-striped table-bordered table-hover">
	<caption>Nén các file js</caption>
	<thead>
		<tr>
			<td>Tên file</td>
			<td>Trạng thái</td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td>{DATAJS}</td>
			<td><div id="jsnb_{JSF.number}"></div></td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: js -->
<script type="text/javascript">
    var myjsfile = new Array();
    <!-- BEGIN: jsfile -->
    myjsfile[{JSF.number}]='{JSF.name}';
    <!-- END: jsfile -->
</script>
<script type="text/javascript">
    //<![CDATA[
    var timer = 0;
    var timer_is_on = 0;
    var count = 0;

    var i_count_file = 0;
    function nv_compiler_js()
    {
        if(!timer_is_on)
        {
            clearTimeout(timer);
            timer_is_on = 0;

            if(i_count_file <= myjsfile.length)
            {
                jsfile = myjsfile[i_count_file];
                $.post(nv_base_siteurl + 'index.php', nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=js&jsfile=' + jsfile + '&rand=' + nv_randomPassword(8), function(theResponse)
                {
                	var r_split = theResponse.split("_");
					
                    if(r_split[0] == 'OK')
                    {
                        $("#jsnb_" + i_count_file).html('Ok');
						timer = setTimeout("nv_compiler_js()", 500);
                    }
                    else
                    {
                        $("#jsnb_" + i_count_file).html('Error');
						timer = setTimeout("nv_compiler_js()", 500);
                    }
                    i_count_file = i_count_file + 1;
                });
            }
            else
            {
                alert('Thuc chien xong');
            }
        }
    }

    if(myjsfile.length > 0)
    {
        nv_compiler_js();
    }
    //]]>
</script>
<!-- END: main -->