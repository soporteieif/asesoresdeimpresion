{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if isset($tags)}
	<table cellpadding="5" style="width:100%">
        <tr>
            <td colspan="2">
                <input type="text" id="txt_tag" value="" size="20" />
                <input type="button" id="btn_add_tag" value="{l s='Add Tag'}" />
            </td>
        </tr>
		<tr><td colspan="2" style="padding-bottom:10px;"><div class="separation"></div></td></tr>
		<tr>
			<td colspan="2">
				<table cellspacing="0" cellpadding="0" class="table tableDnD">
						<thead>
						<tr class="nodrag nodrop"> 
                            <th style="width:80px;">{l s="ID"}</th>
							<th style="width:100px;">{l s='Tag'}</th>
							<th style="width:100px;">{l s='AssocToBlog'}</th>
							<th style="width:100px;" class="center">{l s='Action'}</th>
						</tr>
						</thead>
						<tbody id="tagList">
						</tbody>
				</table>
			</td>
		</tr>
	</table>

	<table id="tagLineType" style="display:none;">
		<tr id="tag_id">
            <td>td_id</td>
			<td style="padding:4px;">
				tag_name
			</td>
			<td>
				<input type="checkbox" assoc_checked class="assocTo" name="id_tag_assoc" id="id_tag_assoc_tag_id" value="tag_id" />
			</td>
			<td class="center">
				<a href="#" class="tag_action" >
					<img src="../img/admin/delete.gif" class="delete_tag" alt="{l s='Delete this tag'}" title="{l s='Delete this tag'}" />
				</a>
			</td>
		</tr>
	</table>

	<script type="text/javascript">
		var token = '{$token}';
		{literal}
		//Ready Function
		$(document).ready(function(){
			{/literal}
			{foreach from=$tags item=tag}
				tagLine({$tag.id_st_blog_tag}, "{$tag.name}", "{$object->isAssocToTag($object->id, $tag.id_st_blog_tag)}");
			{/foreach}
			{literal}

			/**
			 * on success function 
			 */
			function afterDeleteTag(data)
			{
				data = $.parseJSON(data);
				if (data)
				{
				    if (data.error)
                        showErrorMessage(data.error);
                    else
                    {
                        id = data.id;
    					if(data.success)
    					{
    						$("#" + id).remove();
    					}					
    					showSuccessMessage(data.success);   
                    }
				}
			}
            
            function afterAddTag(data)
			{
				data = $.parseJSON(data);
				if (data)
				{
				    if (data.error)
                        showErrorMessage(data.error);
                    else
                    {
                        if(data.new)
    					{
    						tagLine(data.id_tag, $('#txt_tag').val().trim(), 1);
    					}					
    					showSuccessMessage(data.success);    
                    }
				}
			}

			$('.delete_tag').die().live('click', function(e)
			{
				e.preventDefault();
				id = $(this).parents('tr').attr('id');
				if (confirm("{/literal}{l s='The action will delete the tag with it\'s associated. Are you sure?' js=1}{literal}"))
				doAdminAjax({
						"action":"DeleteBlogTag",
						"id_st_blog_tag":id,
                        "id_st_blog":"{/literal}{$object->id}{literal}",
						"token" : "{/literal}{$token}{literal}",
						"tab" : "AdminStBlog",
						"ajax" : 1 }, 
                        afterDeleteTag
				);
			});
            
            $('#btn_add_tag').die().live('click', function(e)
			{
				var tag =$('#txt_tag').val();
				doAdminAjax({
						"action":"AddBlogTag",
						"tag":tag,
                        "id_st_blog":"{/literal}{$object->id}{literal}",
						"token" : "{/literal}{$token}{literal}",
						"tab" : "AdminStBlog",
						"ajax" : 1 }, 
                        afterAddTag
				);
			});
			
			$('.assocTo').die().live('click', function()
			{
				id = $(this).parent().parent().attr('id');
				doAdminAjax(
				{
					"action":"AssocToTag",
					"id_st_blog_tag":id,
					"id_st_blog": "{/literal}{$object->id}{literal}",
					"token" : "{/literal}{$token}{literal}",
					"tab" : "AdminStBlog",
					"ajax" : 1
				});
			});

			function tagLine(id, name, assoc)
			{
				line = $("#tagLineType").html();
				line = line.replace(/tag_id/g, id);
				line = line.replace(/tag_name/g, name);
                line = line.replace(/td_id/g, id);
                line = line.replace(/assoc_checked/g, assoc?'checked="true"':'');
				line = line.replace(/<tbody>/gi, "");
				line = line.replace(/<\/tbody>/gi, "");
				
				$("#tagList").append(line);
			}
		});
		{/literal}
	</script>
{/if}
