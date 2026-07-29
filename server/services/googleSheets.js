const { google } = require('googleapis');
require('dotenv').config();

const getAuthClient = () => {
  const auth = new google.auth.JWT(
    process.env.GOOGLE_SHEETS_CLIENT_EMAIL,
    null,
    process.env.GOOGLE_SHEETS_PRIVATE_KEY.replace(/\\n/g, '\n'),
    ['https://www.googleapis.com/auth/spreadsheets']
  );
  return auth;
};

const getSheetsClient = () => {
  const auth = getAuthClient();
  return google.sheets({ version: 'v4', auth });
};

const SPREADSHEET_ID = process.env.GOOGLE_SHEETS_SPREADSHEET_ID;
const SHEET_NAME = 'Sheet1';

async function appendToSheet(values) {
  const sheets = getSheetsClient();
  try {
    await sheets.spreadsheets.values.append({
      spreadsheetId: SPREADSHEET_ID,
      range: `${SHEET_NAME}!A:G`,
      valueInputOption: 'USER_ENTERED',
      insertDataOption: 'INSERT_ROWS',
      resource: { values }
    });
    return true;
  } catch (error) {
    console.error('Google Sheets append error:', error.message);
    throw new Error('Failed to save order to Google Sheets: ' + error.message);
  }
}

async function getOrdersFromSheet() {
  const sheets = getSheetsClient();
  try {
    const response = await sheets.spreadsheets.values.get({
      spreadsheetId: SPREADSHEET_ID,
      range: `${SHEET_NAME}!A:G`
    });
    const rows = response.data.values || [];
    if (rows.length <= 1) return [];
    return rows.slice(1);
  } catch (error) {
    console.error('Google Sheets read error:', error.message);
    return [];
  }
}

module.exports = { appendToSheet, getOrdersFromSheet };
