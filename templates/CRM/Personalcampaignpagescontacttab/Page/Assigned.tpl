<div class="crm-block crm-content-block crm-discount-view-form-block">

    <table class="crm-info-panel">
        <tr>
            <th class="label">{ts}Page title{/ts}</th>
            <th class="label">{ts}Status{/ts}</th>
            <th class="label">{ts}Contribution page or event{/ts}</th>
            <th class="label">{ts}No of contributions{/ts}</th>
            <th class="label">{ts}Amount raised{/ts}</th>
            <th class="label">{ts}Target amount{/ts}</th>
            <th></th>
        </tr>
        {foreach from=$rows item=row}
            {if $row}
                <tr>
                    <td><a href="{crmURL p='civicrm/pcp/info' q="reset=1&id=`$row.id`" fe='true'}" title="{ts}View Personal Campaign Page{/ts}" target="_blank">{$row.page_title}</a></td>
                    <td>{$row.status}</td>
                    <td><a href="{$row.page_url}" title="{ts}View page{/ts}" target="_blank">{$row.contribution_page_or_event}</a></td>
                    <td>{$row.no_of_contributions}</td>
                    <td>{$row.amount_raised}</td>
                    <td>{$row.target_amount}</td>
                    <td id={$row.id}>{$row.action|replace:'xx':$row.id}</td>
                </tr>
            {/if}
        {/foreach}
        </tr>
    </table>
</div>