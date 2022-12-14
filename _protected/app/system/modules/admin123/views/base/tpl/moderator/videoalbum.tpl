<div class="center">
    {if !empty($albums)}
        <ul>
            {each $album in $albums}

                {{ $action = ($album->approved == 1) ? 'disapprovedvideoalbum' : 'approvedvideoalbum' }}
                {{ $absolute_url = Framework\Mvc\Router\Uri::get('video','main','album',"$album->username,$album->name,$album->albumId") }}

                <div class="thumb_photo">
                    <a href="{absolute_url}" target="_blank">
                        <img src="{url_data_sys_mod}video/file/{% $album->username %}/{% $album->albumId %}/{% $album->thumb %}" alt="{% $album->name %}" title="{% $album->name %}" />
                    </a>
                    <p class="italic">
                        {lang 'Posted by'} {{ $design->getProfileLink($album->username) }}<br />
                        <small>{lang 'Posted on %0%', $album->createdDate}</small>
                    </p>

                    <div>
                        {{ $text = ($album->approved == 1) ? t('Disapproved') : t('Approved') }}
                        {{ LinkCoreForm::display($text, PH7_ADMIN_MOD, 'moderator', $action, array('album_id'=>$album->albumId, 'id'=>$album->profileId)) }} |
                        {{ LinkCoreForm::display(t('Delete'), PH7_ADMIN_MOD, 'moderator', 'deletevideoalbum', array('album_id'=>$album->albumId, 'id'=>$album->profileId, 'username'=>$album->username)) }}
                    </div>
                </div>

            {/each}
        </ul>
        {main_include 'page_nav.inc.tpl'}
    {else}
        <p>{lang 'No Video Albums found for moderation treatments.'}</p>
    {/if}
</div>
