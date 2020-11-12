import win32com.client
import random
import string
import xlrd


class InDesignTextReplacement():
    app = win32com.client.Dispatch('InDesign.Application.CC.2019')
    app.scriptPreferences.userInteractionLevel = 1699640946

    def __init__(self, indesign_file_path, excel_file_path, skip_white_spaces):
        self.indesign_file_path = indesign_file_path
        self.excel_file_path = excel_file_path
        self.indesign_document = app.Open(indesign_file_path)
        self.excel_file = xlrd.open_workbook(self.excel_file_path)
        self.texts = []

    def set_texts(self):
        worksheet = self.excel_file.sheet_by_index(0)
        first_column_values = worksheet.col_values(0)
        second_column_values = worksheet.col_values(1)
        self.texts = [(first_column_values[i], second_column_values[i]) for i in range(len(first_column_values))]

    def sort_texts(self):
        self.texts.sort(key = lambda x: x[0], reverse=True)   

    def replace_texts(self):
        for text_tuple in self.texts:
            for text_frame in indesign_document.TextFrames:
                if text_tuple[0] in (text_frame.Contents):
                    app.FindTextPreferences.FindWhat = text_tuple[0]
                    app.ChangeTextPreferences.ChangeTo = text_tuple[1]
                    text_frame.ChangeText()
