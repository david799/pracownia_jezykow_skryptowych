import win32com.client
import random
import string
import xlrd

"""workbook = xlrd.open_workbook('C:\\Users\\atheelm\\Documents\\python excel mission\\errors1.xlsx')
workbook = xlrd.open_workbook('C:\\Users\\atheelm\\Documents\\python excel mission\\errors1.xlsx', on_demand = True)
worksheet = workbook.sheet_by_index(0)
first_row = [] # The row where we stock the name of the column
for col in range(worksheet.ncols):
    first_row.append( worksheet.cell_value(0,col) )
# tronsform the workbook to a list of dictionnary
data =[]
for row in range(1, worksheet.nrows):
    elm = {}
    for col in range(worksheet.ncols):
        elm[first_row[col]]=worksheet.cell_value(row,col)
    data.append(elm)
print data"""


class InDesignTextReplacement():
    app = win32com.client.Dispatch('InDesign.Application.CC.2019')
    app.scriptPreferences.userInteractionLevel = 1699640946

    def __init__(self, indesign_file_path, excel_file_path, skip_white_spaces):
        self.indesign_file_path = indesign_file_path
        self.excel_file_path = excel_file_path
        self.indesign_document = app.Open(indesign_file_path)
        self.excel_file = workbook = xlrd.open_workbook(self.excel_file_path)
        translation_texts = docx_file.get_translate_texts()
        i = 0

        for it in indesign_document.TextFrames:
            print("weszlo")
            if translation_texts[i][0] in (it.Contents):
                app.FindTextPreferences.FindWhat = translation_texts[i][0]
                app.ChangeTextPreferences.ChangeTo = translation_texts[i][1]
                it.ChangeText()
            i = i + 1
