{*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.7                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2017                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*}
{if $action eq 1 or $action eq 2 or $action eq 4 or $action eq 8}
  {include file="CRM/Materialbestellung/Form/Material.tpl"}
{else}
  <div class="help">
    <p>{ts}This page shows all the Forum ZFD material. All active ones will be published on the website.{/ts}</p>
  </div>
  {if $rows}
    {if !($action eq 1 and $action eq 2)}
      <div class="action-link">
        {crmButton q="action=add&reset=1" class="newMaterial" icon="plus-circle"}{ts}Add Material{/ts}{/crmButton}
      </div>
    {/if}

    <div id="fzfd_material">

      {strip}
        {include file="CRM/common/jsortable.tpl"}
        <table id="options" class="display">
          <thead>
          <tr>
            <th>{ts}Title{/ts}</th>
            <th>{ts}Price{/ts}</th>
            <th>{ts}Category{/ts}</th>
            <th>{ts}Creation Year{/ts}</th>
            <th>{ts}Language{/ts}</th>
            <th>{ts}Number of Pages{/ts}</th>
            <th>{ts}Download Link{/ts}</th>
            <th>{ts}Can be ordered?{/ts}</th>
            <th>{ts}Active?{/ts}</th>
            <th></th>
          </tr>
          </thead>
          {foreach from=$rows item=row}
            <tr id="material-{$row.id}" class="crm-entity {cycle values="odd-row,even-row"} {$row.class}">
              <td class="crm-material-title" data-field="title">{$row.title}</td>
              <td class="crm-material-price" data-field="price">{$row.price|crmMoney}</td>
              <td class="crm-material-category" data-field="material_category_id">{$row.material_category_id}</td>
              <td class="crm-material-creation_year" data-field="creation_year">{$row.creation_year}</td>
              <td class="crm-material-language" data-field="language_id">{$row.language_id}</td>
              <td class="crm-material-number_of_pages" data-field="number_of_pages">{$row.number_of_pages}</td>
              {if !empty($row.download_link)}
                <td class="crm-material-download_link"><a href="{$row.download_link}">{$row.download_link}</a></td>
              {else}
                <td class="crm-material-download_link">&nbsp;</td>
              {/if}
              {if $row.can_be_ordered eq 1}
                <td class="crm-material-can_be_ordered">{ts}Yes{/ts}</td>
              {else}
                <td class="crm-material-can_not_be_ordered">{ts}No{/ts}</td>
              {/if}
              {if $row.is_active eq 1}
                <td class="crm-material-is_active">{ts}Yes{/ts}</td>
              {else}
                <td class="crm-material-is_inactive">{ts}No{/ts}</td>
              {/if}
              <td>{$row.action|replace:'xx':$row.id}</td>
            </tr>
          {/foreach}
        </table>
      {/strip}

    </div>
  {else}
    <div class="messages status no-popup">
      <img src="{$config->resourceBase}i/Inform.gif" alt="{ts}status{/ts}"/>
      {ts}None found.{/ts}
    </div>
  {/if}
  <div class="action-link">
    {crmButton q="action=add&reset=1" class="newMaterial" icon="plus-circle"}{ts}Add Material{/ts}{/crmButton}
    {crmButton p="civicrm/" q="reset=1" class="cancel" icon="times"}{ts}Done{/ts}{/crmButton}
  </div>
{/if}
{literal}
  <script type="text/javascript">
    //jQuery to retrieve and show category label and language label
    cj('.crm-material-category').each(function() {
      var category = cj(this);
      CRM.api3('OptionValue', 'getvalue', {
        "option_group_id": "fzfd_material_category",
        "value":category.text(),
        "return":"label"})
        .done(function(result) {
          category.html(result.result);
        });
      });
    cj('.crm-material-language').each(function() {
      var language = cj(this);
      CRM.api3('OptionValue', 'getvalue', {
        "option_group_id": "languages",
        "name":language.text(),
        "return":"label"})
      .done(function(result) {
        language.html(result.result);
        });
      });
    //jQuery to process enable, disable and delete
    cj('.action-item').each(function () {
      var itemTitle = cj(this).attr('title');
      var parentItem = cj(this).parent().parent().parent().parent().parent();
      var parentId = parentItem.attr('id');
      switch (itemTitle) {
        case "Enable Material":
          var enableFunction = 'enOrDisableMaterial(1, ' + parentId + ')';
          cj(this).attr('onClick', enableFunction);
          break;
        case "Disable Material":
          cj(this).on("click", enOrDisableMaterial(0, parentId));
          console.log('on click toegevoegd');
          break;
        case "Delete Material":
          var deleteFunction = 'deleteMaterial(' + parentId + ')';
          cj(this).attr('onClick', deleteFunction);
          break;
      }
    });
  </script>
{/literal}

