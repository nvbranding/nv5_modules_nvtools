<!-- BEGIN: main -->
<h1>Chức năng copy các cấu hình ngôn ngữ tiếng việt của site sang ngôn ngữ khác (copy các module mặc định và cấu hình site)</h1>
<p>
    <b>Hướng dẫn thực hiện:</b>
    <br>
    <br>
    - Cài đặt site đa ngôn ngữ
    <br>
    - Chọn ngôn ngữ muốn copy sang
    <br>
</p>

<!-- BEGIN: tables -->
<p class="text-success"><strong>Thực hiện xong:</strong></p>
<pre><code>{TABLES}</code></pre>
<!-- END: tables -->

<div class="panel panel-default">
    <div class="panel-body">
        <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" class="form-inline">
            Chọn ngôn ngữ muốn copy: 
            <select name="lang" class="form-control">
                <option value="{LANG_I}">{LANG_I}</option>
            </select>
            <input name="submit_copy" type="submit" value="Thực hiện" class="btn btn-primary">
        </form>
    </div>
</div>

<!-- END: main -->