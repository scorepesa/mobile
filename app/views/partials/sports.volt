 <table class="sports">
 <th class="title">Other sports</th>
    <tr>
      <td>
        <?php
          $id='';
          $categoryID='';
          $competitionID='';
          ?>
          <ul class="aoi">
              {#Changed to /upcoming route#}
         <!--  <li class=""><b><a class="lgue" href="{{url('upcoming')}}">Upcoming Events</a></b></li> -->
          <?php foreach($sports as $key => $sp): ?>

          <?php if($id != $sp['sport_id'] && $sp['sport_id'] != '79'): ?>
          <li class="">
          <div class="sport-header title">
            <span class=""> {{sp['sport_name']}} </span>
          </div>
          <ul class="sport">
          <?php $id=$sp['sport_id']; ?>
          <?php foreach($sports as $s): ?>

            <?php $url=$sportType[$s['sport_id']].'?id='.$s['competition_id']; ?>
            <?php if($id == $s['sport_id'] && $categoryID != $s['category_id']): ?>
               <li class="competition"><span>
               <span class="gothb"> {{s['category']}} </span>
                </span>

                <ul class="sport">
                <?php $categoryID=$s['category_id']; ?>
                <?php foreach($sports as $st): ?>
                  <?php
                  $url=$sportType[$s['sport_id']].'?id='.$st['competition_id'];
                  $competitionID=$st['competition_id'];
                  ?>
                  <?php if($categoryID == $st['category_id']): ?>
                    <li>
                        <a class="lgue" href="{{url(url)}}">
                          <span> {{st['competition_name']}}  </span>
                          <span class="pull-right "
                      style="margin-right:10px; color:#fff; margin-top:5px">{{st['games']}}</span>
                        </a> 
                        
                    </li>
                  <?php endif; ?>
                  <?php $categoryID=$s['category_id']; ?>
                <?php endforeach; ?>
                </ul>

                </li>
            <?php endif; ?>
          <?php endforeach; ?>
          </ul>
          </li>
          <?php endif; ?>

          <?php endforeach; ?>
          </ul>
      </td>
    </tr>
  </table>