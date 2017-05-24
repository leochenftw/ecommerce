<h1 class="hide">$Title</h1>
<div class="container">
    <div class="columns">
        <div class="column is-12">
            <% with $FragmentForm.addExtraClass('is-relative') %>
                <form $FormAttributes>
                    $Fields.fieldByName('Content').FieldHolder
                    $Fields.fieldByName('SecurityID').FieldHolder
                    <div class="actions is-absolute">
                        <div class="columns">
                            <div class="column is-auto-width">$Fields.fieldByName('StoryID').FieldHolder</div>
                            <div class="column" style="padding-left: 0; padding-right: 0;">$Fields.fieldByName('ChapterID').FieldHolder</div>
                            <div class="column is-auto-width">$Actions.First</div>
                        </div>
                    </div>
                </form>
            <% end_with %>
            <div class="fragments">
                <h2 class="title is-2">Loose fragments</h2>
                <% if $Fragments %>
                    <div class="fragments__list">
                        <% loop $Fragments %>
                            <div class="fragments__list__item">
                                $Content
                            </div>
                        <% end_loop %>
                    </div>
                    <div class="fragments__pagination">
                    <% if $Fragments.MoreThanOnePage %>
                        <% if $Fragments.NotFirstPage %>
                            <a class="button is-primary prev" href="$Fragments.PrevLink">‹</a>
                        <% end_if %>
                        <% if $Fragments.NotLastPage %>
                            <a class="button is-primary next" href="$Fragments.NextLink">›</a>
                        <% end_if %>
                    <% end_if %>
                    </div>
                <% else %>
                    <p>- no fragment -</p>
                <% end_if %>
            </div>
        </div>
    </div>
</div>
$Content
