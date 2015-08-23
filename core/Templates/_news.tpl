{foreach $items as $item}
	<div class="news">
		<h3><a href="/news/{$item.alias}">{$item.pagetitle}</a></h3>
		<p>{$item.text}</p>
		{if $item.cut}
			<a href="/news/{$item.alias}" class="btn btn-default">Читать далее &rarr;</a>
		{/if}
	</div>
{/foreach}