[{capture append="oxidBlock_content"}]
    [{oxstyle include=$oViewConf->getModuleUrl('tremendo_dhlstatus','out/src/css/dhlstatus.css')}]
    [{assign var="trackingData" value=$oView->getTrackingData()}]
    [{assign var="events" value=$trackingData->events}]
    <h1 class="page-header">[{oxmultilang ident="TREMENDO_DHLSTATUS_SHIPMENTTRACKING"}]</h1>
    <div class="tremendo-dhlstatus-timeline">
        <ol>
        [{foreach from=$events item="event"}]
            <li class="[{$event->statusCode}]">
                <div class="timestamp">
                    [{$event->timestamp|@date_format:"%a, %d %b %Y,%n%H:%M"}]
                </div>
                <div class="event">
                    [{if $event->statusCode == 'pre-transit'}]
                        [{oxmultilang ident="TREMENDO_DHLSTATUS_PRETRANSITMESSAGE"}]
                    [{else}]
                        [{$event->description}]
                        [{if $event->statusCode == 'delivered'}]
                            <br>
                            [{oxmultilang ident="TREMENDO_DHLSTATUS_DELIVEREDMESSAGE"}]
                        [{/if}]
                    [{/if}]
                </div>
            </li>
        [{/foreach}]
        </ol>
    </div>
    [{$trackingData->events|@debug_print_var}]
[{/capture}]
[{capture append="oxidBlock_sidebar"}]
    [{include file="page/account/inc/account_menu.tpl" active_link="newsletter"}]
[{/capture}]
[{include file="layout/page.tpl" sidebar="Left"}]