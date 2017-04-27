;
var handle_apply_to = function(that,txt_a,txt_b,txt_c)
{
    var val = $(that).val();
    if (val == "PRODUCT")
	{
		val = prompt(txt_a);
		if (val == null || val == "" || isNaN(val))
			return;
		$(that).find("option[value='PRODUCT']").val('1-'+val).text(txt_b+" "+val);
	}
    else if(val=="")
    {
        $(that).find("option[value^='1']").val("PRODUCT").text(txt_c);
    }
    else
    {
        $(that).find("option[value^='1']").val("PRODUCT").text(txt_c);
    } 
};