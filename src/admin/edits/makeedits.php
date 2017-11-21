<? session_start(); ?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{$ctrl.title}}, {{$ctrl.who.fname}}</h3>
  </div>
  <div class="panel-body">
    <div class="row">
      <div class="col-sm-12" ng-if="!$ctrl.editing">
        <div class="row">
          <div class="col-sm-12">
            Howdy <? echo $_SESSION['fullname'] ?>. How would you like to search
            for the resource to edit or delete? <br /><br />
          </div>
          <div class="col-sm-12">
            <div class="row" ng-if="<? echo $_SESSION['level'] ?>==super">
              <div class="col-sm-3">
                <button type="button" class="btn btn-primary btn-block"
                    ng-click="$ctrl.howSearch('title')">BY TITLE</button>
              </div>
              <div class="col-sm-3">
                <button type="button" class="btn btn-primary btn-block"
                    ng-click="$ctrl.howSearch('tag')">BY TAG</button>
              </div>
              <div class="col-sm-3">
                <button type="button" class="btn btn-primary btn-block"
                    ng-click="$ctrl.howSearch('person')">BY PERSON</button>
              </div>
              <div class="col-sm-3">
                <button type="button" class="btn btn-primary btn-block"
                    ng-click="$ctrl.howSearch('latest')">LATEST UPLOADS</button>
              </div>
            </div>
            <div class="row" ng-if="<? echo $_SESSION['level'] ?>!=super">
              <div class="col-sm-4">
                <button type="button" class="btn btn-primary btn-block"
                    ng-click="$ctrl.howSearch('title')">BY TITLE</button>
              </div>
              <div class="col-sm-4">
                <button type="button" class="btn btn-primary btn-block"
                    ng-click="$ctrl.howSearch('tag')">BY TAG</button>
              </div>
              <div class="col-sm-4">
                <button type="button" class="btn btn-primary btn-block"
                    ng-click="$ctrl.howSearch('latest')">LATEST UPLOADS</button>
              </div>
            </div>
            <div class="col-sm-12" ng-if="$ctrl.showForm" ng-cloak>
              <br /><br />
              <form ng-submit="$event.preventDefault()" name='getForm' novalidate>
                <div ng-if="$ctrl.notag" class="alert alert-danger" role="alert">
                  We don't have anything that matches that request. Please try again.</div>
                <md-autocomplete
                    ng-disabled="$ctrl.isDisabled"
                    md-no-cache="$ctrl.noCache"
                    md-selected-item="$ctrl.selectedItem"
                    md-search-text="$ctrl.searchText"
                    md-selected-item-change="$ctrl.selectedItemChange(item)"
                    md-items="item in $ctrl.querySearch($ctrl.searchText)"
                    md-item-text="item.display"
                    md-min-length="0"
                    placeholder="type, type like the wind" ng-model="$ctrl.search"
                    class="input-element">
                  <md-item-template>
                    <span md-highlight-text="$ctrl.searchText" md-highlight-flags="^i">{{item.display}}</span>
                  </md-item-template>
                  <md-not-found>
                    No matches "{{$ctrl.searchText}}" were found.
                  </md-not-found>
                </md-autocomplete>
                <br/>
                <button type="submit" class="btn btn-primary"
                        ng-click = "$ctrl.goSearch()">GO GET THOSE RESOURCES</button><br/>
                </form>
            </md-content>
            </div>
            <div class="col-sm-12" ng-if="$ctrl.showSuperList"><br /><hr />
              <show-list
                whatdoing = 'editing'
                level="$ctrl.who.level"
                title="LATEST UPLOADS"
                list="$ctrl.ed_resources"
                edit-action="$ctrl.editUpdate(index)"
                delete-action="$ctrl.deleteUpdate(index)"></show-list>
            </div>
            <div class="col-sm-12" ng-if="$ctrl.toEdit">
              <br /><hr />
              <show-list
                whatdoing = 'editing'
                level="$ctrl.who.level"
                title="HERE IS WHAT WE HAVE"
                list="$ctrl.ed_resources"
                edit-action="$ctrl.editUpdate(index)"
                delete-action="$ctrl.deleteUpdate(index)"></show-list>
            </div>
            <div>
              <div class="col-sm-12" ng-if="$ctrl.gotoEdit">
                <br /><hr />
                <add-edit
                  what= 'edit'
                  who=  "$ctrl.who"
                  resource = "$ctrl.which_resource"
                  clear-search = "$ctrl.clearSearch(actionType)"></add-edit>
              </div>
              <br/>
              <div ng-if="$ctrl.updated" class="alert alert-success col-sm-12" role="alert">
          Resource was successfully updated and is currently listed as pending. One of the super admins
        will need to approve it so go bug them to do so.</div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete "{{$ctrl.which_resource.title}}"</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class=row>
          <div ng-if="$ctrl.madeDelete" class="alert alert-success" role="alert">
            Entry has been {{$ctrl.whathappened}}.</div>
          <div ng-if="$ctrl.level=='user' && !$ctrl.madeDelete">
            <div  ng-if="$ctrl.which_resource.doshow!='p'"
                class="alert alert-warning" role="alert" class="col-sm-12">
              This entry has either been approved or reduced
              from approved to pending by one of the super admin such that you cannot
              delete the resource at this time. Feel free to go yell at one of the
              super admins about this.
            </div>
            <div class="col-sm-12" ng-if="$ctrl.which_resource.doshow=='p'">
            Are you sure you want to delete this entry? Whatever you've entered will be
            gone, lost, forgotten, goodbye.
           </div>
          </div>


         <div ng-if="$ctrl.level=='super'">
           <div class="col-sm-12"
                ng-if="$ctrl.which_resource.doshow=='s'">
             Given that this resource has been approved, you have the option of deleting
             it or reducing it to pending status. Both states make it such that the
             resource can't be viewed publically, but if there's a chance you may want
             to make this resoure available again, please just reduce it to pending.
           </div>
           <div class="col-sm-12"
                ng-if="$ctrl.which_resource.doshow!='s'">
             The current status of this entry is pending.
             Deleting this entry will still keep it in the database but won't be so easily
             recoverable. Do you dare click that DELETE button?
           </div>
         </div>

       </div>

      </div>

      <div class="modal-footer">
        <div ng-if="!$ctrl.madeDelete">
           <button type="button" class="btn btn-secondary" data-dismiss="modal" >CANCEL</button>
          <? if ($_SESSION['level']=='super') { ?>
            <button type="button" class="btn btn-primary"
          ng-click="$ctrl.doDelete('delete')">DELETE</button>
          <button ng-if="$ctrl.which_resource.doshow=='s'"
          type="button" class="btn btn-primary"
          ng-click="$ctrl.doDelete('pending')">REDUCE TO PENDING</button>
          <? } else { ?>
            <button ng-if="$ctrl.which_resource.doshow=='p'"
                  type="button" class="btn btn-primary"
          ng-click="$ctrl.doDelete('delete')">DELETE</button>
          <? } ?>
        </div>
        <div ng-if="$ctrl.madeDelete">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"
          ng-click="$ctrl.resetAll()">CLOSE</button>
        </div>

      </div>

    </div>
  </div>
</div>
