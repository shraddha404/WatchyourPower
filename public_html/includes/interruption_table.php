<div class="mailbox" style="">
        <div class="table-responsive">
                <table class="table table-mailbox" style="width:80%; margin-left:15%;">
                        <tbody>
                        <tr>
                        <td class="small-col" ><strong>Interruption Pattern</strong></td>
                        <td class="small-col" ><strong>Number of <br/>Interruptions</strong></td>
                        <td class="small-col" ><strong>No Supply <br/>Duration (HH:MM)</strong></td>
                        </tr>

                        <tr class="unread" id="short-interruption">
                        <td class="small-col">
			<!--<img src="/img/plus.png" id="first_plus" style="float:left;" onclick="$('#first_minus').show(); $(this).hide();"/>
			<img src="/img/minus.png" id="first_minus" style="float:left; display:none;" onclick="$('#first_plus').show(); $(this).hide();"/>-->
			&nbsp;&nbsp;<strong>Short interruptions</strong></td>
			<td class="small-col"><?php echo $interrupt_duration['short_interruptions']; ?>	</td>
			<td class="small-col"><?php echo secondsToTime($interrupt_duration['supply_short_interruptions']); ?></td>
                        </tr>

                        <tr id="short-interruption-first">
                        <td class="small-col" >Less than Fifteen Minutes <br />(2 - 15 minutes)</td>
                        <td class="small-col" ><?php echo $interrupt_duration['less_than_15_minutes']; ?></td>
                        <td class="small-col" ><?php echo secondsToTime($interrupt_duration['supply_less_than_15_minutes']); ?></td>
                        </tr>

                        <tr id="short-interruption-second">
                        <td class="small-col" >15 Minutes to 1 Hour <br />(16 - 60 minutes)</td>
                        <td class="small-col" ><?php echo $interrupt_duration['between_16_to_60']; ?></td>
                        <td class="small-col" ><?php echo secondsToTime($interrupt_duration['supply_between_16_to_60']); ?></td>
                        </tr>

                        <tr class="unread" id="long-interruption">
			<td class="small-col" >
			<!--<img src="/img/plus.png" id="plus" style="float:left;" onclick="$('#minus').show(); $(this).hide();"/>
			<img src="/img/minus.png" id="minus" style="float:left; display:none;" onclick="$('#plus').show(); $(this).hide();"/>-->
			&nbsp;&nbsp;<strong>Long interruptions</strong></td>
			<td class="small-col"><?php echo $interrupt_duration['long_interruptions']; ?></td>
			<td class="small-col"><?php  /*secondsToTime($interrupt_duration['supply_long_interruptions']);*/
echo secondsToTime($interrupt_duration['supply_more_than_180'] + $interrupt_duration['supply_between_61_to_180']);?></td>
                        </tr>

                        <tr id="long-interruption-first">
                        <td class="small-col" >One to three hours <br /> (61 - 180 minutes)</td>
                        <td class="small-col" ><?php echo $interrupt_duration['between_61_to_180']; ?></td>
                        <td class="small-col" ><?php echo secondsToTime($interrupt_duration['supply_between_61_to_180']); ?></td>
                        </tr>

                        <tr id="long-interruption-second">
                        <td class="small-col" >More than three hours</td>
                        <td class="small-col" ><?php echo $interrupt_duration['more_than_180']; ?></td>
                        <td class="small-col" ><?php echo secondsToTime($interrupt_duration['supply_more_than_180']); ?></td>
                        </tr>

                        <tr class="unread">
                        <td class="small-col" ><strong>Total</strong></td>
                        <td class="small-col" ><?php echo $interrupt_duration['interrupt_total']; ?></td>
                        <td class="small-col" ><?php echo secondsToTime($interrupt_duration['supply_interrupt_total']); ?></td>
                        </tr>
                        </tbody>
                </table>
</div>
</div>

