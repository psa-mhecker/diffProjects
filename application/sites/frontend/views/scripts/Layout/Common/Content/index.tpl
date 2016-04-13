<ul>
{section name=index loop=$list}
                                            <li>
                                                <a href="{urlParser url=$list[index].url}">{$list[index].title}</a>
                                            </li>
{/section}
</ul>