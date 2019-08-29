<table class="deposit">
  <th class="title">Deposit</th>
  <tr>
    <td style="padding: 5px;">
      <p>
        Enter amount below, use your service pin to authorize the transaction. If you do not have a service pin, please follow the instructions to set.
      </p>
      {{ this.flashSession.output() }}
    </td>
  </tr>
  <tr>
    <td style="padding: 5px;">
        <table class="form">
        <?php echo $this->tag->form("deposit/topup"); ?>
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
              <button type="submit" onclick="fbDeposit()">Deposit Now</button>
            </td>
          </tr>
      </form>
        </table>
      </form>
    </td>
  </tr>
</table>
