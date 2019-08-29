<table class="withdraw">
                <th class="title">Withdraw</th>
                <tr>
                  <td style="padding: 5px;">
                    <p>
                      Enter amount below to initiate transaction
                    </p>
                    {{ this.flashSession.output() }}
                  </td>
                </tr>
                <tr>
                  <td style="padding: 5px;">
                      <table class="form">
                      <?php echo $this->tag->form("withdraw/withdrawal"); ?>
                        <tr class="input">
                          <td>
                            <div>
                              <label for="amount">Amount (Ksh) *</label>
                              <input type="number" name="amount" placeholder="Ksh">
                            </div>
                          </td>
                        </tr>
                        <tr class="spacer"></tr>
                        <tr class="input">
                          <td>
                            <button type="submit">Withdraw Now</button>
                          </td>
                        </tr>
                        </form>
                      </table>
                    </form>
                  </td>
                </tr>
                
              </table>