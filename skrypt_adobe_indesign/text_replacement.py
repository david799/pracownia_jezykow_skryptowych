from os import listdir
from os.path import isfile, join
from xlutils.copy import copy
import xml.etree.ElementTree as ET
import random
import string
import xlrd
import xlwt

from simple_idml import idml
from simple_idml.extras import create_idml_package_from_dir


class InDesignTextReplacement():
    def __init__(self, indesign_file_path, excel_file_path, skip_white_spaces):
        self.indesign_file_path = indesign_file_path
        self.excel_file_path = excel_file_path
        self.indesign_document = idml.IDMLPackage(indesign_file_path)
        self.excel_file = xlrd.open_workbook(self.excel_file_path)
        self.skip_white_spaces = skip_white_spaces
        self.text_frames_contents = []
        self.excel_texts = []

    def set_texts(self):
        worksheet = self.excel_file.sheet_by_index(0)
        first_column_values = worksheet.col_values(0)
        second_column_values = worksheet.col_values(1)
        self.excel_texts = [[first_column_values[i], second_column_values[i], 0, i] for i in range(len(first_column_values))]

    def sort_texts(self):
        self.excel_texts.sort(key = lambda x: x[0])

    def set_up(self):
        self.set_texts()
        self.sort_texts()

    def create_idml_package(self, path):
        create_idml_package_from_dir(path, path + '_new.idml')

    def replace_texts(self):
        extract_to = './' + self.indesign_file_path.split('.')[-2].split('/')[-1]
        self.indesign_document.extractall(extract_to)
        stories_path = join(extract_to, './Stories')
        story_files = [join(stories_path, f) for f in listdir(stories_path) if isfile(join(stories_path, f))]
        xmls_and_paths = []

        for story_file in story_files:
            tree = ET.parse(story_file)
            xmls_and_paths.append(
                {
                    "xml": tree,
                    "path": story_file
                }
            )
            root = tree.getroot()
            for content in root.iter('Content'):
                self.text_frames_contents.append(content)

        for text_tuple in self.excel_texts:
            for content in self.text_frames_contents:
                if text_tuple is not None and content is not None:
                    if content.text is not None:
                        if self.skip_white_spaces:
                            text_frame_without_spaces = " ".join(content.text.split())
                            excel_text_without_spaces = " ".join(text_tuple[0].split())
                            if excel_text_without_spaces in text_frame_without_spaces:
                                content.text = content.text.replace(text_tuple[0], text_tuple[1])
                                text_tuple[2] = 1
                        else:
                            if text_tuple[0] in (content.text):
                                content.text = content.text.replace(text_tuple[0], text_tuple[1])
                                text_tuple[2] = 1

        for xml_and_path in xmls_and_paths:
            xml = xml_and_path["xml"]
            path = xml_and_path["path"]
            xml.write(path)
        self.create_idml_package(extract_to)

        writable_excel_copy = copy(self.excel_file)
        writable_sheet = writable_excel_copy.get_sheet(0)
        st = xlwt.easyxf('pattern: pattern solid, fore_colour red;')
        for text_tuple in self.excel_texts:
            if not text_tuple[2]:
                writable_sheet.write(text_tuple[3], 0, text_tuple[0], st)
        writable_excel_copy.save("not_replaced_texts_underlined.xls")

        return True, len(self.excel_texts), sum([text_tuple[2] for text_tuple in self.excel_texts])
