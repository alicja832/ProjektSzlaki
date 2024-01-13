<form name="form">            
      <table>
          <tr><td><label for="imie">login:</label></td>
          <td><input value="<?php if(isset($formData)) echo $formData['login']; ?>" type="text" id="login" name="login" /></td></tr>
          <tr><td><label for="haslo">Hasło:</label></td>
          <td><input value="<?php if(isset($formData)) echo $formData['haslo']; ?>" type="password" id="haslo" name="haslo" /></td></tr>
          <tr><td></td><td><span id="data"><input type="button" value="Zarejestruj" onclick="rejestruj()" /></span></td></tr>
          <tr><td></td><td><span id="data"><input type="button" value="Zaloguj" onclick="log()" /></span></td>
          <tr><td></td><td><span id="response"></span></td></tr>
      </table>
    </form>
<div class="image2">
 <img src="obraz1.jpg" width="400" height="500"></img>
 </div>