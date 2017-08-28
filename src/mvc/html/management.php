<div class="bbn-100">
  <div class="bbn-w-100" v-bbn-fill-height style="overflow: hidden">
    <div id="RRsj983Jfjnv2kasihj234" class="bbn-full-height"></div>
  </div>
  <?php foreach ( $templates as $tpl ){ ?>
  <?=$tpl?>
  <?php } ?>
</div>

<!-- CDN tabstrip -- >
<div class="bbn-100">
  <div id="pe49ajAssj3knvVvn323" class="bbn-h-100">
    <ul>
      <li class="k-state-active" style="width: 49%; text-align: center">
        <i class="fa fa-archive"></i> <?=_("Libraries")?>
      </li>
      <li style="width: 49%; text-align: center">
        <i class="fa fa-cogs"></i> <?=_("Configurations")?>
      </li>
    </ul>
    <div class="bbn-full-height" style="padding: 0; margin: 0; border: 0">
      <!-- All libraries grid -- >
      <div id="RRsj983Jfjnv2kasihj234" class="bbn-h-100"></div>
    </div>
    <div class="bbn-full-height" style="padding: 0; margin: 0; border: 0">
      <!-- All configurations grid -- >
      <div id="98324nas9t4pash9d2n3ifau" class="bbn-h-100"></div>
    </div>
  </div>
  <?php foreach ( $templates as $tpl ){ ?>
  <?=$tpl?>
  <?php } ?>
</div>


<!-- bbn-table :source="source.all_lib">
  <table>
    <thead>
    <tr>
      <th title="Title"
          field="title"
      ></th>
      <th title="Folder's name"
          field="name"
      ></th>
      <th title="Function's name"
          field="fname"
      ></th>
      <th title="Latest"
          field="latest"
      ></th>
      <th title="Author"
          field="author"
          width="70"
          render="showAuthor"
      ></th>
      <th title="Licence"
          field="licence"
          width="70"
          render="showLicence"
      ></th>
      <th title="Site"
          field="website"
          width="70"
          render="showSite"
      ></th>
      <th title="Download"
          field="download_link"
          width="70"
          render="showDownload"
      ></th>
      <th title="Doc"
          field="doc_link"
          width="70"
          render="showDoc"
      ></th>
      <th title="Git"
          field="git"
          width="70"
          render="showGit"
      ></th>
      <th title="Support"
          field="support_link"
          width="70"
          render="showSupport"
          class="bbn-c"
      ></th>
      <th width="100"
          field="name"
          title="Actions"
          buttons="[
              {command: 'edit', icon: 'fa fa-edit', text: 'Edit', notext: true},
              {command: 'remove', icon: 'fa fa-trash', text: 'Delete', notext: true}
            ]"
      ></th>
    </tr>
    </thead>
  </table>
</bbn-table-->