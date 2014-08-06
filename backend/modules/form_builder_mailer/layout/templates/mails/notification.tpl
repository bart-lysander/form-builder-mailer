{include:head_part1.tpl}
<title>{$title}</title>
{include:head_part2.tpl}

<h2>{$title}</h2>
<hr/>
<br/>

<p>
    {$ReceivedData|ucfirst}:
</p>
<p>
    {$fields|nl2br}
</p>

{include:footer.tpl}
