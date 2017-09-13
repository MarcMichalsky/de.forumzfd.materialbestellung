{* HEADER *}

<div class="crm-block crm-form-block">
  <div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="top"}
  </div>
  {foreach from=$elementNames item=elementName}
    <div class="crm-section">
      <div class="label">{$form.$elementName.label}</div>
      <div class="content">{$form.$elementName.html}
      {if $elementName == "download_link"}
        &nbsp;<a id="download_link_url" href="{$form.$elementName.value}">{$form.$elementName.value}</a>
      {/if}
      </div>
      <div class="clear"></div>
    </div>
  {/foreach}

  {* FOOTER *}
  <div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
</div>

{* jQuery to either show URL or editable input for download_link in view/edit mode *}
{literal}
  <script type="text/javascript">
    var downloadLinkReadonly = cj('#download_link').attr('readonly');
    if (downloadLinkReadonly !== "readonly") {
      cj('#download_link_url').hide();
    } else {
      cj('#download_link').hide();
    }
  </script>
{/literal}
