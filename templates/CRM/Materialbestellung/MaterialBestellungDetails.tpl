<h2>Data von Website Bestellung:</h2>
<table>
  <tr>
    <th>{ts}Field{/ts}</th>
    <th>{ts}Value{/ts}</th>
  </tr>
  {if isset($material_id)}
    <tr>
      <td>{ts}Material ID{/ts}</td>
      <td>{$material_id}</td>
    </tr>
  {/if}
  {if isset($contact_id)}
    <tr>
      <td>{ts}Contact ID{/ts}</td>
      <td>{$contact_id}</td>
    </tr>
  {/if}
  {if isset($quantity)}
    <tr>
      <td>{ts}Quantity{/ts}</td>
      <td>{$quantity}</td>
    </tr>
  {/if}
  {if isset($prefix_id)}
    <tr>
      <td>{ts}Prefix ID{/ts}</td>
      <td>{$prefix_id}</td>
    </tr>
  {/if}
  {if isset($first_name)}
    <tr>
      <td>{ts}First Name{/ts}</td>
      <td>{$first_name}</td>
    </tr>
  {/if}
  {if isset($last_name)}
    <tr>
      <td>{ts}Last Name{/ts}</td>
      <td>{$last_name}</td>
    </tr>
  {/if}
  {if isset($email)}
    <tr>
      <td>{ts}Email{/ts}</td>
      <td>{$email}</td>
    </tr>
  {/if}
  {if isset($street_address)}
    <tr>
      <td>{ts}Street Address{/ts}</td>
      <td>{$street_address}</td>
    </tr>
  {/if}
  {if isset($postal_code)}
    <tr>
      <td>{ts}Postal Code{/ts}</td>
      <td>{$postal_code}</td>
    </tr>
  {/if}
  {if isset($city)}
    <tr>
      <td>{ts}City{/ts}</td>
      <td>{$city}</td>
    </tr>
  {/if}
  {if isset($country_iso_code)}
    <tr>
      <td>{ts}Country (ISO code){/ts}</td>
      <td>{$country_iso_code}</td>
    </tr>
  {/if}
  {if isset($source)}
    <tr>
      <td>{ts}Source{/ts}</td>
      <td>{$source}</td>
    </tr>
  {/if}
</table>