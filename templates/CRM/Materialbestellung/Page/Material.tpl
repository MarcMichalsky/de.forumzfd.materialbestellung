<div class="crm-content-block crm-block">
  <div id="help">
    {ts}This page shows all available material. All active items will be listed on the website.{/ts}
  </div>
  <div class="action-link">
    <a class="button new-option" href="{$addUrl}">
      <span><i class="crm-i fa-plus-circle">&nbsp;</i>New Material</span>
    </a>
  </div>

  {include file="CRM/common/pager.tpl" location="top"}
  {include file='CRM/common/jsortable.tpl'}
  <div id="fzfd_material-wrapper" class="dataTables_wrapper">
    <table id="fzfd_material-table" class="display">
      <thead>
      <tr>
        <th>{ts}Title{/ts}</th>
        <th>{ts}Price{/ts}</th>
        <th>{ts}Category{/ts}</th>
        <th>{ts}Creation Year{/ts}</th>
        <th>{ts}Language{/ts}</th>
        <th>{ts}Number of Pages{/ts}</th>
        <th>{ts}Download Link{/ts}</th>
        <th>{ts}Active?{/ts}</th>
        <th id="nosort"></th>
      </tr>
      </thead>
      <tbody>
      {assign var="rowClass" value="odd-row"}
      {assign var="rowCount" value=0}
      {foreach from=$materialRows key=materialId item=material}
        <tr id="row_{$materialId}" class="crm-entity {cycle values="odd-row,even-row"} {$row.class}">
          <td hidden="1">{$materialId}</td>
          <td>{$material.title}</td>
          <td>{$material.price|crmMoney}</td>
          <td>{$material.material_category}</td>
          <td>{$material.creation_year}</td>
          <td>{$material.language}</td>
          <td>{$material.number_of_pages}</td>
          {if !empty($material.download_link)}
            <td><a href="{$material.download_link}">{$material.download_link}</a></td>
          {else}
            <td>&nbsp;</td>
          {/if}
          {if $material.is_active eq 1}
            <td>{ts}Yes{/ts}</td>
          {else}
            <td>{ts}No{/ts}</td>
          {/if}
          <td>
              <span>
                {foreach from=$material.actions item=actionLink}
                  {$actionLink}
                {/foreach}
              </span>
          </td>
        </tr>
      {/foreach}
      </tbody>
    </table>
  </div>
  {include file="CRM/common/pager.tpl" location="bottom"}
  <div class="action-link">
    <a class="button new-option" href="{$addUrl}">
      <span><i class="crm-i fa-plus-circle">&nbsp;</i>New Material</span>
    </a>
  </div>
</div>