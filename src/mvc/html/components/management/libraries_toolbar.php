<!-- Libraries toolbar -->
<!--script type="text/html" line324 id="kasdnjnsdfguiwerhasdh"-->
<div class="toolbar">
  <div style="float: left; display: inline-block">
    <span style="margin-right: 5px"><i class="fa fa-archive"></i><?=_('Libraries:')?></span>

    <!-- Add library button -->
    <bbn-button @click="add"
                icon="fa fa-plus"
                style="margin-right: 5px"
    >
      <?=_("Add")?>
    </bbn-button>

    <!-- GitHub Updates-->
    <span style="margin: 0 5px 0 10px"><i class="fa fa-github"></i>GitHub:</span>
    <bbn-button @click="checkUpdate"
                icon="fa fa-refresh"
                style="margin-right: 5px"
                title="<?=_("Check updates from GitHub")?>"
    >
      <?=_("Check updates")?>
    </bbn-button>
    <!-- TODO incomplete-->
    <bbn-button @click="showUpdate"
                icon="fa fa-cubes"
                style="margin-right: 5px"
                title="<?=_("Show updates")?>"
                :disabled= "disabledButton"
    >
      <?=_('Updates')?>
    </bbn-button>
  </div>
  <!-- Search library -->
  <!-- TODO-->
  <div style="float: right; display: inline-block">
    <i class="fa fa-search" style="margin: 0 5px"></i>
    <bbn-input style="width: 300px"
               placeholder="Search library"
               @keydown.enter="searchLibrary"
    ></bbn-input>
  </div>
</div>
