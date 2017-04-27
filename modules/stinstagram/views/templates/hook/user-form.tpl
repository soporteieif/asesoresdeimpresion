<script type="text/javascript">
//<![CDATA[
{literal}
function connect_instagram(){
	var params = new Array();
	params['client_id'] = '{/literal}{$client_id}{literal}';
	params['redirect_uri'] = '{/literal}{$redirect_uri}{literal}?url='+encodeURIComponent(location.href);
    params['scope'] = '{/literal}{$scope}{literal}';
	params['response_type'] = '{/literal}{$response_type}{literal}';

	var form = document.createElement('form');
	form.setAttribute('method', 'get');
	form.setAttribute('action', '{/literal}{$action}{literal}');

	for(var key in params)
    {
		if(params.hasOwnProperty(key)) 
        {
			var hidden = document.createElement('input');
			hidden.setAttribute('type', 'hidden');
			hidden.setAttribute('name', key);
			hidden.setAttribute('value', params[key]);
			form.appendChild(hidden);
		}
	}
	document.body.appendChild(form);
	form.submit();
}
{/literal}
//]]>
</script>
<style style="text/css">
#a_linker{
    color:#f00;
    font-weight:bold;
    display:block;
    height:30px;
    line-height:30px;
}
</style>
<a href="javascript:;" id="a_linker" onclick="connect_instagram();">{l s="Click here connect to instagram firstly."}</a>