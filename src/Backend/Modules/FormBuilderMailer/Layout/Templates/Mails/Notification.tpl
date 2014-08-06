{include:/FormBuilderMailer/Layout/Templates/Mails/HeadPart1.tpl}
<title>{$title}</title>
{include:/FormBuilderMailer/Layout/Templates/Mails/HeadPart2.tpl}

<h2>{$title}</h2>
<hr/>
<br/>

<p>
    {$ReceivedData|ucfirst}:
</p>
<p>
    {$fields|nl2br}
</p>

{include:/FormBuilderMailer/Layout/Templates/Mails/Footer.tpl}
