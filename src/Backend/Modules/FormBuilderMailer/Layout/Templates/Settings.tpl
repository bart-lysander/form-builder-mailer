{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
	<h2>{$lblModuleSettings|ucfirst}: {$lblFormBuilderMailer}</h2>
</div>

{form:settings}

	<div class="box">
		<div class="heading">
			<h3>{$lblSettings|ucfirst}</h3>
		</div>
		<div class="options">
			<ul class="inputList">
				<li>
                    <label for="enabled">{$chkEnabled} {$lblModuleEnabled|ucfirst}</label>
                    <span class="helpTxt">{$msgHelpEnabled}</span>
                </li>
				<li>
                    <label for="log">{$chkLog} {$lblLog|ucfirst}</label>
                    <span class="helpTxt">{$msgHelpLog}</span>
                </li>
				<li>
                    <label for="add_data">{$chkAddData} {$lblAddData|ucfirst}</label>
                    <span class="helpTxt">{$msgHelpAddData}</span>
                </li>
			</ul>
		</div>
	</div>

	<div class="fullwidthOptions">
		<div class="buttonHolderRight">
			<input id="save" class="inputButton button mainButton" type="submit" name="save" value="{$lblSave|ucfirst}" />
		</div>
	</div>
{/form:settings}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
